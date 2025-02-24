<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.profile`)" class="p-0">
                <template #extra>
                    <a-button type="primary" @click="onSubmit">
                        <template #icon> <SaveOutlined /> </template>
                        {{ $t("common.update") }}
                    </a-button>
                </template>
            </a-page-header>
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t("menu.settings") }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t("menu.profile") }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <a-row>
        <a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
            <SettingSidebar />
        </a-col>
        <a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
            <admin-page-table-content>
                <a-card class="page-content-container mt-20 mb-20">
                    <a-form layout="vertical">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    label="Header"
                                    name="head"
                                    :help="rules.name ? rules.name.message : null"
                                    :validateStatus="rules.name ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.head"
                                        placeholder="Header"
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    label="Sub Header"
                                    name="sub_header"
                                    :help="rules.name ? rules.name.message : null"
                                    :validateStatus="rules.name ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.sub_head"
                                        placeholder="Header"
                                    />
                                </a-form-item>
                            </a-col>
                        </a-row>



                    </a-form>
                </a-card>
            </admin-page-table-content>
        </a-col>
    </a-row>
</template>
<script>
import { onMounted, ref } from "vue";
import {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        EyeOutlined,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        ExclamationCircleOutlined,
        SaveOutlined,

        Upload,
        SettingSidebar,
        AdminPageHeader,
    },
    setup() {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { t } = useI18n();
        const store = useStore();
        const formData = ref({});
        const currencies = ref({});
        const user = store.state.auth.user;

        console.log(user);

        onMounted(() => {

            const bannerPromise = axiosAdmin.get("banner");

            Promise.all([bannerPromise]).then(
                ([bannerPromise]) => {
                    console.log(bannerPromise.data);
                    formData.value = bannerPromise.data;
                    setFormData();
                }
            );

            formData.value = {
                head: formData.head,
                sub_head: formData.sub_head,
            };

        });

        const onSubmit = () => {

            addEditRequestAdmin({
                url: `banner`,
                data: formData.value,
                successMessage: 'Banner updated',
                success: (res) => {

                },
            });
        };

        return {
            loading,
            rules,
            formData,
            currencies,
            onSubmit,
        };
    },
};
</script>
