<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Lead\IndexRequest;
use App\Http\Requests\Api\Lead\StoreRequest;
use App\Http\Requests\Api\Lead\UpdateRequest;
use App\Http\Requests\Api\Lead\DeleteRequest;
use App\Http\Requests\Api\Lead\CreateLeadRequest;
use App\Http\Requests\Api\Lead\CreateBookingRequest;
use App\Http\Requests\Api\Lead\SendEmailRequest;
use App\Http\Requests\Api\Lead\StartFollowRequest;
use App\Models\Campaign;
use App\Models\CampaignUser;
use App\Models\Lead;
use App\Models\LeadLog;
use App\Models\Salesman;
use App\Models\Settings;
use App\Models\lead_count;
use App\Notifications\SendLeadMail;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Examyou\RestAPI\ApiResponse;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\Notification;

class LeadController extends ApiBaseController
{
    protected $model = Lead::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyIndex($query)
    {
        $request = request();
        $user = user();

        // Extra Filter For campaign Type
        $query = $query->join('campaigns', 'campaigns.id', '=', 'leads.campaign_id');


        if ($user->ability('admin', 'view_completed_campaigns')) {
            // Filter By Campaign Status
            if ($request->has('campaign_status') && $request->campaign_status == "completed") {
                $query = $query->where('status', '=', 'completed');
            } else {
                $query = $query->where('status', '!=', 'completed');
            }
        } else {
            $query = $query->where('status', '!=', 'completed');
        }

        // Extra Filters
        if ($request->has('lead_field_name') && $request->lead_field_name != "" && $request->has('lead_field_value') && $request->lead_field_value != "") {
            $filterStringOne = 'field_name":"' . $request->lead_field_name . '","field_value":"' . $request->lead_field_value;
            $filterStringTwo = "field_name':'" . $request->lead_field_name . "','field_value':'" . $request->lead_field_value;
            $query = $query->where('lead_data', 'like', '%' . $filterStringOne . '%')
                ->orWhere('lead_data', 'like', '%' . $filterStringTwo . '%');
        }

        if ($request->has('lead_status') && $request->lead_status != '') {
            $query = $query->where('leads.lead_status', $request->lead_status);
        }

        // Started Filter
        $started = $request->has('started') && $request->started == 'not_started' ? 0 : 1;
        $query = $query->where('started', $started);

        // If user either not admin or have leads_view_all permissions
        // then lead last_action_by must be logged in user
        // and only leads started will be visible
        if ($user->ability('admin', 'leads_view_all')) {
            if ($started) {
                $userId = $request->has('user_id') && $request->user_id != "" ? $request->user_id : $user->id;
                $query = $query->where('leads.last_action_by', $userId);
            }
        } else {
            $query = $query->where('leads.last_action_by', $user->id)
                ->where('leads.started', 1);
        }

        return $query;
    }

    public function createLead(CreateLeadRequest $request)
    {
        $user = user();


        if (!$user->ability('admin', 'leads_create')) {
            throw new ApiException("Not Allowed");
        }

        $xCampaignId = $request->campaign_id;
        $campaignId = $this->getIdFromHash($xCampaignId);
        $loggedUser = user();
        $campaign = Campaign::find($campaignId);

        // Calculating Lead Data Hash
        $leadHashString = "";
        $leadDatas = $request->lead_data;
        foreach ($leadDatas as $leadData) {
            $leadHashString .= strtolower($leadData['field_value']);
        }

        $lead = new Lead();
        $lead->campaign_id = $campaignId;

        // Reference Prefix
        if ($campaign->allow_reference_prefix) {
            $lead->reference_number = $campaign->reference_prefix . Carbon::now()->timestamp;
        }

        $lead->lead_data = $request->lead_data;
        $lead->created_by = $loggedUser->id;
        $lead->lead_hash = md5($leadHashString . $campaignId);
        $lead->save();

        // Calculating Lead Counts
        Common::recalculateCampaignLeads($campaignId);

        return ApiResponse::make('Success', []);
    }

    public function createLeadCallLog($leadXId)
    {
        $id = $this->getIdFromHash($leadXId);
        $loggedUser = user();

        $lead = Lead::find($id);

        // Recalculate Time Taken in Lead
        // And insert it in lead
        $recalculateLeadTime = LeadLog::where('lead_id', $lead->id)
            // ->where('user_id', '=', $loggedUser->id)
            ->where('log_type', '=', 'call_log')
            ->sum('time_taken');
        $lead->time_taken = $recalculateLeadTime;
        $lead->save();

        // TODO - Check if any other user is not attending this lead
        $callLog = new LeadLog();
        $callLog->lead_id = $lead->id;
        $callLog->log_type = 'call_log';
        $callLog->user_id = $loggedUser->id;
        $callLog->started_on = (int)$lead->time_taken;
        $callLog->time_taken = 0;
        $callLog->date_time = Carbon::now();
        $callLog->save();

        $leadNumber = Lead::where('id', '<=', $lead->id)
            ->where('campaign_id', $lead->campaign_id)
            ->count();


        return ApiResponse::make('Success', [
            'call_log' => $callLog,
            'lead_number' => $leadNumber
        ]);
    }

    public function createBooking(CreateBookingRequest $request)
    {
        $request = request();
        $bookingType = $request->booking_type;
        $leadXId = $request->lead_id;
        $id = $this->getIdFromHash($leadXId);

        $lead = Lead::find($id);

        // TODO - Check if any other user is not attending this lead

        $bookingId = $bookingType == 'lead_follow_up' ? $lead->lead_follow_up_id : $lead->salesman_booking_id;

        if (!is_null($bookingId)) {
            $booking = LeadLog::where('log_type', $bookingType)
                ->where('id', $bookingId)
                ->first();
        }


        if (is_null($bookingId) || (!is_null($bookingId) && !$booking)) {
            $booking = new LeadLog();
            $booking->lead_id = $lead->id;
            $booking->log_type = $bookingType;
        }

        $booking->date_time = $request->date_time;
        $booking->user_id = $request->user_id;
        $booking->notes = $request->notes;
        $booking->save();

        if ($bookingType == 'lead_follow_up') {
            $lead->lead_follow_up_id = $booking->id;
        } else {
            $lead->salesman_booking_id = $booking->id;
        }
        $lead->save();

        $bookingData = LeadLog::select('id', 'date_time', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->find($booking->id);

        return ApiResponse::make('Success', [
            'booking' => $bookingData
        ]);
    }

    public function leadCampaignMembers()
    {
        $request = request();
        $bookingType = $request->booking_type;
        $leadXId = $request->lead_id;
        $id = $this->getIdFromHash($leadXId);

        $lead = Lead::select('id', 'campaign_id')->find($id);

        // TODO - Check if any other user is not attending this lead

        $users = [];

        if ($bookingType == 'lead_follow_up') {
            $users = CampaignUser::select('users.id', 'users.name')
                ->join('users', 'users.id', '=', 'campaign_users.user_id')
                ->where('campaign_users.campaign_id', $lead->campaign_id)
                ->get();
        } else if ($bookingType == 'salesman_bookings') {
            $users = Salesman::select('id', 'name')->get();
        }

        return ApiResponse::make('Success', [
            'users' => $users
        ]);
    }

    public function startFollowUp(StartFollowRequest $request)
    {
        $request = request();
        $bookingType = $request->log_type;
        $leadXId = $request->x_lead_id;
        $id = $this->getIdFromHash($leadXId);

        $lead = Lead::find($id);

        // TODO - Check if any other user is not attending this lead

        $booking = LeadLog::where('log_type', $bookingType)
            ->where('lead_id', $lead->id)
            ->first();

        if (!$booking) {
            $booking = new LeadLog();
            $booking->lead_id = $lead->id;
            $booking->log_type = $bookingType;
        }

        $booking->date_time = $request->date_time;
        $booking->user_id = $request->user_id;
        $booking->notes = $request->notes;
        $booking->save();

        $bookingData = LeadLog::select('id', 'date_time', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->find($booking->id);

        return ApiResponse::make('Success', []);
    }

    public function leadCampaignStats()
    {
        $request = request();
        $user = user();

        // Total Active/Completed Campaign Counts
        $totalActiveCampaign = Campaign::where('campaigns.status', '!=', 'completed');
        $totalCompletedCampaign = Campaign::where('campaigns.status', '=', 'completed');

        // Total Leads
        $totalLeads = Lead::join('campaigns', 'campaigns.id', '=', 'leads.campaign_id');
        $callMade = Lead::join('campaigns', 'campaigns.id', '=', 'leads.campaign_id')
            ->where('started', 1);
        $totalDuration = Lead::join('campaigns', 'campaigns.id', '=', 'leads.campaign_id');


        if (!$user->ability('admin', 'leads_view_all')) {
            $totalActiveCampaign = $totalActiveCampaign->join('campaign_users', 'campaign_users.campaign_id', '=', 'campaigns.id')
                ->where('campaign_users.user_id', $user->id);
            $totalCompletedCampaign = $totalCompletedCampaign->join('campaign_users', 'campaign_users.campaign_id', '=', 'campaigns.id')
                ->where('campaign_users.user_id', $user->id);

            $callMade = $callMade->where('leads.last_action_by', $user->id);
            $totalDuration = $totalDuration->where('leads.last_action_by', $user->id);
        }

        if ($request->has('campaign_status') && $request->campaign_status == 'completed') {
            $totalLeads = $totalLeads->where('campaigns.status', '=', 'completed');
            $callMade = $callMade->where('campaigns.status', '=', 'completed');
            $totalDuration = $totalDuration->where('campaigns.status', '=', 'completed');
        } else {
            $totalLeads = $totalLeads->where('campaigns.status', '!=', 'completed');
            $callMade = $callMade->where('campaigns.status', '!=', 'completed');
            $totalDuration = $totalDuration->where('campaigns.status', '!=', 'completed');
        }

        if ($request->has('campaign_id') && $request->campaign_id != '') {
            $campaignId = $this->getIdFromHash($request->campaign_id);
            $totalLeads = $totalLeads->where('campaigns.id', $campaignId);
            $callMade = $callMade->where('campaigns.id', $campaignId);
            $totalDuration = $totalDuration->where('campaigns.id', $campaignId);
        }


        $totalActiveCampaign = $totalActiveCampaign->count();
        $totalCompletedCampaign = $totalCompletedCampaign->count();
        $totalLeads = $totalLeads->count();
        $callMade = $callMade->count();
        $totalDuration = $totalDuration->sum('time_taken');


        return ApiResponse::make('Success', [
            'total_active_campaign' => $totalActiveCampaign,
            'total_completed_campaign' => $totalCompletedCampaign,
            'total_leads' => $totalLeads,
            'call_made' => $callMade,
            'total_duration' => $totalDuration,
        ]);
    }

    public function sendEmail(SendEmailRequest $request)
    {
        $user = user();
        $email = $request->email;
        $subject = $request->subject;
        $message = $request->message;
        $success = false;
        $xLeadId = $request->lead_id;
        $leadId = $this->getIdFromHash($xLeadId);
        $mailSetting = Settings::withoutGlobalScope(CompanyScope::class)->where('setting_type', 'email')
            ->where('name_key', 'smtp')
            ->first();


        if ($mailSetting && $mailSetting->status && $mailSetting->verified) {
            $mailSent = true;

            try {
                Notification::route('mail', $email)->notify(new SendLeadMail($subject, $message));
            } catch (\Exception $exception) {

                $mailSent = false;
            }

            // TODO - insert mail
            if ($mailSent) {
                $leadLog = new LeadLog();
                $leadLog->lead_id = $leadId;
                $leadLog->log_type = 'email';
                $leadLog->user_id = $user->id;
                $leadLog->date_time = Carbon::now();
                $leadLog->notes = json_encode([
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                ]);
                $leadLog->save();
            }

            $success = true;
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }


    public function updateCount(Request $request){
        try {
            //code...
            $user = user();
            $total_leads = $request->total_leads;
            $lead_count = lead_count::where('user_id',$user->id)->update([
                'total_leads' => $total_leads,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }


}
