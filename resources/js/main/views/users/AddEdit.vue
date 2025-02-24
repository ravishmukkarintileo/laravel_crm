<template>
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
    >
        <a-form layout="vertical">
            <a-row>
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('user.profile_image')"
                        name="profile_image"
                        :help="rules.profile_image ? rules.profile_image.message : null"
                        :validateStatus="rules.profile_image ? 'error' : null"
                    >
                        <Upload
                            :formData="formData"
                            folder="user"
                            imageField="profile_image"
                            @onFileUploaded="
                                (file) => {
                                    formData.profile_image = file.file;
                                    formData.profile_image_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('user.adhaar')"
                        name="adhaar"
                        :help="rules.profile_image ? rules.profile_image.message : null"
                        :validateStatus="rules.profile_image ? 'error' : null"
                    >
                        <Upload
                            :formData="formData"
                            folder="user"
                            imageField="adhaar"
                            @onFileUploaded="
                                (file) => {
                                    formData.adhaar = file.file;
                                    formData.adhaar_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('user.pan_card')"
                        name="pan_card"
                        :help="rules.profile_image ? rules.profile_image.message : null"
                        :validateStatus="rules.profile_image ? 'error' : null"
                    >
                        <Upload
                            :formData="formData"
                            folder="user"
                            imageField="pan_card"
                            @onFileUploaded="
                                (file) => {
                                    formData.pan_card = file.file;
                                    formData.pan_card_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('user.pan_card')"
                        name="pan_card"
                        :help="rules.profile_image ? rules.profile_image.message : null"
                        :validateStatus="rules.profile_image ? 'error' : null"
                    >
                        <Upload
                            :formData="formData"
                            folder="user"
                            imageField="check_copy"
                            @onFileUploaded="
                                (file) => {
                                    formData.check_copy = file.file;
                                    formData.check_copy_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="18" :lg="18">
                    <a-row :gutter="16" v-if="permsArray.includes('admin')">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-form-item
                                :label="$t('user.role')"
                                name="role_id"
                                :help="rules.role_id ? rules.role_id.message : null"
                                :validateStatus="rules.role_id ? 'error' : null"
                                class="required"
                            >
                                <span style="display: flex">
                                    <a-select
                                        v-model:value="formData.role_id"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                $t('user.role'),
                                            ])
                                        "
                                        :allowClear="true"
                                    >
                                        <a-select-option
                                            v-for="role in roles"
                                            :key="role.xid"
                                            :value="role.xid"
                                        >
                                            {{ role.display_name }}
                                        </a-select-option>
                                    </a-select>
                                    <RoleAddButton @onAddSuccess="roleAdded" />
                                </span>
                            </a-form-item>
                        </a-col>
                    </a-row>
                  <a-row :gutter="16" v-if="permsArray?.includes('admin')">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-form-item
                            :label="$t('branch.select_branch')"
                            name="branch_id"

                            class="required"
                        >
                            <a-select
                                v-model:value="formData.branch_id"
                                :placeholder="$t('common.select_default_text', [$t('branch.name')])"
                                :allowClear="true"
                                style="width: 100%"
                            >
                                <a-select-option
                                    v-for="branch in branches"
                                    :key="branch.id"
                                    :value="branch.id"
                                >
                                    {{ branch.name }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                   </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('user.name')"
                                name="name"
                                :help="rules.name ? rules.name.message : null"
                                :validateStatus="rules.name ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('user.name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('user.phone')"
                                name="phone"
                                :help="rules.phone ? rules.phone.message : null"
                                :validateStatus="rules.phone ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.phone"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('user.phone'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row>
                        <a-col :span="24">
                            <a-form-item
                                :label="$t('user.password')"
                                name="password"
                                :help="rules.password ? rules.password.message : null"
                                :validateStatus="rules.password ? 'error' : null"
                                class="required"
                            >
                                <a-input-password
                                    v-model:value="formData.password"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('user.password'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('user.email')"
                                name="email"
                                :help="rules.email ? rules.email.message : null"
                                :validateStatus="rules.email ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.email"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('user.email'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('user.status')"
                                name="status"
                                :help="rules.status ? rules.status.message : null"
                                :validateStatus="rules.status ? 'error' : null"
                                class="required"
                            >
                                <a-select
                                    v-model:value="formData.status"
                                    :placeholder="
                                        $t('common.select_default_text', [
                                            $t('user.status'),
                                        ])
                                    "
                                    :allowClear="true"
                                >
                                    <a-select-option value="enabled"
                                        >Enabled</a-select-option
                                    >
                                    <a-select-option value="disabled"
                                        >Disabled</a-select-option
                                    >
                                </a-select>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-col>

            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('user.address')"
                        name="address"
                        :help="rules.address ? rules.address.message : null"
                        :validateStatus="rules.address ? 'error' : null"
                    >
                        <a-textarea
                            v-model:value="formData.address"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('user.address'),
                                ])
                            "
                            :auto-size="{ minRows: 2, maxRows: 3 }"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button
                type="primary"
                @click="onSubmit"
                style="margin-right: 8px"
                :loading="loading"
            >
                <template #icon> <SaveOutlined /> </template>
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
            </a-button>
            <a-button @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import { useStore } from "vuex";
import apiAdmin from "../../../common/composable/apiAdmin";
import Upload from "../../../common/core/ui/file/Upload.vue";
import RoleAddButton from "../settings/roles/AddButton.vue";
import common from "../../../common/composable/common";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        Upload,
        RoleAddButton,
    },
    setup(props, { emit }) {
    const { permsArray, user, appSetting } = common();
    const { addEditRequestAdmin, loading, rules } = apiAdmin();
    const store = useStore();

    // Role Data
    const roles = ref([]);
    const roleUrl = "roles?limit=10000";

    // Branch Data
    const branches = ref([]);
    const branchUrl = "branches"; // API endpoint for branches

    // Fetch Roles and Branches on Component Mount
    onMounted(() => {
        const rolesPromise = axiosAdmin.get(roleUrl);
        const branchesPromise = axiosAdmin.get(branchUrl);

        Promise.all([rolesPromise, branchesPromise]).then(([rolesResponse, branchesResponse]) => {
            roles.value = rolesResponse.data;
            branches.value = branchesResponse.data;

        });
    });

    const onSubmit = () => {
         if (!props.formData.branch_id) {
        console.warn("Branch ID is missing! Please select a branch.");
        return; // Stop submission if branch_id is missing
    }

        console.log(props.formData)
        addEditRequestAdmin({
            url: props.url,
            data: props.formData,
            successMessage: props.successMessage,
            success: (res) => {
                emit("addEditSuccess", res.xid);

                if (user.value.xid == res.xid) {
                    store.dispatch("auth/updateUser");
                }
            },
        });
    };

    const onClose = () => {
        rules.value = {};
        emit("closed");
    };

    // Refresh Role List
    const roleAdded = () => {
        axiosAdmin.get(roleUrl).then((response) => {
            roles.value = response.data;
        });
    };

    // Refresh Branch List
    const branchAdded = () => {
        axiosAdmin.get(branchUrl).then((response) => {
            branches.value = response.data;

        });
    };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            roles,
            roleAdded,
            branches, // Make branches available in the template
            branchAdded,
            permsArray,
            appSetting,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
         };
      }

    });
</script>
