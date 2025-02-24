<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.dashboard`)" style="padding: 0px" />
        </template>

    </AdminPageHeader>

    <div class="dashboard-page-content-container">

        <a-row :gutter="[8, 8]" class="mt-30 mb-30">
            <a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
                <DateRangePicker
                    ref="serachDateRangePicker"
                    @dateTimeChanged="
                        (changedDateTime) => (filters.dates = changedDateTime)
                    "
                />
            </a-col>

            <a-col
    v-if="permsArray.includes('admin')"
    :xs="24"
    :sm="24"
    :md="6"
    :lg="6"
>
    <a-select
        :placeholder="$t('common.select_default_text', 'Branch Name')"
        :allowClear="true"
        style="width: 100%"
        @change="setUrlData"
    >
        <a-select-option
            v-for="branch in branches"
            :key="branch.id"
            :value="branch.id"
        >
            {{ branch.name }}
        </a-select-option>
    </a-select>
</a-col>


            <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <h2>Total lead</h2>
                <h2 v-if="responseData.stateData">
                    {{  responseData.getCount }}
                </h2>
            </a-col>
        </a-row>

        <div class="mt-30 mb-20">
            <h3><b>Current Month</b></h3>

            <a-row :gutter="[15, 15]">
                <!-- <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#08979c">
                        <template #image>
                            <CopyrightCircleOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.campaign_count }}
                            </h2>
                            <p v-if="totalFollowUp === 0">
                                <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    {{ $t("dashboard.active_campaigns") }}
                                </router-link>
                            </p>

                            <p v-else>
                            {{ $t("dashboard.active_campaigns") }}
                            </p>
                        </template>
                    </StateWidget>
                </a-col>

                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_follow_ups }}
                            </h2>
                                <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    {{ $t("dashboard.total_follow_up") }}
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>

                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#d46b08">
                        <template #image>
                            <MobileOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.lead_count }}
                            </h2>
                             <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    {{ $t("dashboard.call_made") }}
                                </router-link>

                        </template>
                    </StateWidget>
                </a-col>

                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#ffa39e">
                        <template #image>
                            <ClockCircleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                <small>
                                    {{
                                        formatTimeDuration(
                                            responseData.stateData.total_times
                                        )
                                    }}
                                </small>
                            </h2>
                             <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    {{ $t("dashboard.total_duration") }}
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col> -->

                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_fresh_leads }}
                            </h2>

                            <p v-if="total_follow_ups === 0">
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    Total Fresh Leads
                                </router-link>
                            </p>


                            <p v-else>
                              Fresh Leads
                            </p>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_follow_ups }}
                            </h2>
                                <router-link :to="{ path: '/admin/bookings/lead-follow-up' }" class="text-primary">
                                    <!-- {{ $t("dashboard.follow_up") }} -->
                                    Follow Up
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_callback_leads }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_reminder_leads") }} -->
                                      Total Callback Leads
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_converted_leads }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_converted_leads") }} -->
                                      Total Converted Leads
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_lost_leads }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_reminder_leads") }} -->
                                      Total Lost Leads
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_yet_to_contact_leads }}
                            </h2>
                                <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_converted_leads") }} -->
                                      Total Yet to Contact
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>

            </a-row>
        </div>
        <div class="mt-30 mb-20">
            <h3><b>Next Month</b></h3>
            <a-row :gutter="[15, 15]">

                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_fresh_leads_next_month }}
                            </h2>

                            <p v-if="total_follow_ups === 0">
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    Total Fresh Leads
                                </router-link>
                            </p>


                            <p v-else>
                              Fresh Leads
                            </p>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_followup_leads_next_month }}
                            </h2>
                                <router-link :to="{ path: '/admin/bookings/lead-follow-up' }" class="text-primary">
                                    <!-- {{ $t("dashboard.follow_up") }} -->
                                    Follow Up
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_callback_leads_next_month }}
                            </h2>
                                <router-link :to="{ path: '/admin/call-manager' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_reminder_leads") }} -->
                                      Total Callback
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_converted_leads_next_month }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_converted_leads") }} -->
                                      Total Converted Leads
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_lost_leads_next_month }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_reminder_leads") }} -->
                                      Total Lost Leads
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="8" :xl="8">
                    <StateWidget bgColor="#389e0d">
                        <template #image>
                            <ScheduleOutlined style="color: #fff; font-size: 24px" />
                        </template>
                        <template #description>
                            <h2 v-if="responseData.stateData">
                                {{ responseData.stateData.total_yet_to_contact_leads_next_month }}
                            </h2>
                                <router-link :to="{ path: '/admin/leads' }" class="text-primary">
                                    <!-- {{ $t("dashboard.total_converted_leads") }} -->
                                      Total Yet to Contact
                                </router-link>
                        </template>
                    </StateWidget>
                </a-col>

            </a-row>
        </div>

        <p v-if="totalFollowUp != 0">
            <div><h3 class="text-primary">Please complete your followups to get Fresh Lead Link activated</h3></div>
        </p>

        <a-row :gutter="[18, 18]" class="mt-30 mb-20">
            <a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
                <a-card :title="$t('dashboard.active_actioned_campaigns')">
                    <ActionedCampaigns :data="responseData" />
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="18" :xl="18">
                <a-card :title="$t('dashboard.call_made')">
                    <CallMade :data="responseData" />
                    <template #extra>
                        <a-button
                            class="mt-10"
                            type="link"
                            @click="
                                $router.push({
                                    name: 'admin.leads.index',
                                })
                            "
                        >
                            {{ $t("common.view_all") }}
                            <DoubleRightOutlined />
                        </a-button>
                    </template>
                </a-card>
            </a-col>
        </a-row>

        <a-row :gutter="[18, 18]" class="mt-30 mb-20">
            <a-col :xs="24" :sm="24" :md="12" :lg="16" :xl="16">
                <a-card
                    :title="$t('salesman_booking.salesman_booking')"
                    :bodyStyle="{ padding: '0px' }"
                    v-if="responseData && responseData.allAppointments"
                >
                    <SalesmanBooking :responseData="responseData" />
                    <template #extra>
                        <a-button
                            class="mt-10"
                            type="link"
                            @click="
                                $router.push({
                                    name: 'admin.bookings.salesman_bookings.index',
                                })
                            "
                        >
                            {{ $t("common.view_all") }}
                            <DoubleRightOutlined />
                        </a-button>
                    </template>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                <a-card
                    :title="$t('lead_follow_up.follow_up')"
                    :bodyStyle="{ padding: '0px' }"
                    v-if="responseData && responseData.allFollowUps"
                >
                    <Followups :responseData="responseData" />
                    <template #extra>
                        <a-button
                            class="mt-10"
                            type="link"
                            @click="
                                $router.push({
                                    name: 'admin.bookings.lead_follow_up.index',
                                })
                            "
                        >
                            {{ $t("common.view_all") }}
                            <DoubleRightOutlined />
                        </a-button>
                    </template>
                </a-card>

                <a-card
                    title="Reminders"
                    :bodyStyle="{ padding: '0px' }"
                    v-if="responseData && responseData.allFollowUps"
                >
                    <Reminder :responseData="responseData" />
                    <template #extra>
                        <a-button
                            class="mt-10"
                            type="link"
                            @click="
                                $router.push({
                                    name: 'admin.bookings.lead_follow_up.index',
                                })
                            "
                        >
                            {{ $t("common.view_all") }}
                            <DoubleRightOutlined />
                        </a-button>
                    </template>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch } from "vue";
import {
    CopyrightCircleOutlined,
    MobileOutlined,
    ScheduleOutlined,
    ClockCircleOutlined,
    DoubleRightOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import common from "../../common/composable/common";
import AdminPageHeader from "../../common/layouts/AdminPageHeader.vue";
import StateWidget from "../../common/components/common/card/StateWidget.vue";
import ActionedCampaigns from "../components/charts/dashboard/ActionedCampaigns.vue";
import CallMade from "../components/charts/dashboard/CallMade.vue";
import SalesmanBooking from "./dashboard/SalesmanBookings.vue";
import Followups from "./dashboard/Followups.vue";
import Reminder from "./dashboard/Reminders.vue";
import DateRangePicker from "../../common/components/common/calendar/DateRangePicker.vue";

export default {
    components: {
        AdminPageHeader,
        StateWidget,
        ActionedCampaigns,
        CallMade,
        SalesmanBooking,
        Followups,
        Reminder,
        DateRangePicker,

        MobileOutlined,
        CopyrightCircleOutlined,
        ClockCircleOutlined,
        ScheduleOutlined,
        DoubleRightOutlined,
    },
    setup() {
        const { permsArray, appSetting, formatAmountCurrency } = common();

        const { formatTimeDuration } = common();
        const { t } = useI18n();
        const responseData = ref([]);

        const branches = ref([]);
        const branchUrl = "branches"; // API endpoint for branches

        const filters = reactive({
            dates: [],
        });

        const setUrlData = (d) => {

            const requestData = {
                ...filters, // Spread existing filters
                branch_id: d   // Add `d` as `branch`
            };

            const dashboardPromise = axiosAdmin.post("dashboard", requestData);

            Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
                responseData.value = dashboardResponse.data;
            });
            // crudVariables.tableUrl.value = {
            //     url,
            //     filters,
            //     extraFilters,
            // };

            // crudVariables.table.filterableColumns = filterableColumns;

            // // Setting page number to 1
            // crudVariables.table.pagination = {
            //     ...crudVariables.table.pagination,
            //     current: 1,
            // };
            // crudVariables.fetch({
            //     page: 1,
            // });
        };

        onMounted(() => {
            getInitData();
            setUrlData();

            const branchesPromise = axiosAdmin.get(branchUrl);
            Promise.all([branchesPromise]).then(([branchesResponse]) => {
                branches.value = branchesResponse.data;
            });
        });

        const getInitData = () => {
            const dashboardPromise = axiosAdmin.post("dashboard", filters);

            Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
                responseData.value = dashboardResponse.data;
            });
        };


        watch([filters], (newVal, oldVal) => {
            getInitData();
        });

        return {
            formatTimeDuration,
            filters,
            permsArray,

            responseData,
            branches,
            setUrlData
        };
    },
};
</script>

<style lang="less">
.ant-card-extra,
.ant-card-head-title {
    padding: 0px;
}

.ant-card-head-title {
    margin-top: 10px;
}
</style>
