<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header title="Branches" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        Branches
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    Branches
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        Add
                    </a-button>
                </a-space>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <!-- Using v-slot to define actions column -->
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>

                                    <a-button
                                        v-if="(permsArray.includes('products_delete') || permsArray.includes('admin')) && (!record.children || record.children.length == 0)"
                                        type="secondary"
                                        @click="showDeleteConfirm(record.id)"
                                    >
                                        <DeleteOutlined /> Delete
                                    </a-button>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>

<script>
import { onMounted, ref } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import common from "../../../common/composable/common";
import { add } from "lodash-es";


export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
    },
    setup() {


        const { permsArray, appSetting, formatAmountCurrency } = common();
        const { url, addEditUrl, initData, columns, filterableColumns } = fields();

        const crudVariables = crud();
        // const permsArray = ref(["admin"]); // Example, replace with actual permission logic.


        onMounted(async () => {
            // console.log(crudVariables);

            const params = new URLSearchParams({
                order: "id desc",
                offset: 0,
                limit: 10
            }).toString();

            const fullUrl = `${url}?${params}`;
            // console.log("Fetching from API:", fullUrl);

            // ✅ Assign as an object to avoid TypeError
            crudVariables.tableUrl.value = { url: fullUrl };

            try {
                await crudVariables.fetch({ page: 1 });
                console.log("Table Data After Fetch:", );
                console.log(addEditUrl,"addEditUrl");

                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "branch";
                crudVariables.initData.value = { ...initData };
                crudVariables.formData.value = { ...initData };
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        });

        return {
            columns,
            permsArray,
            ...crudVariables,
        };
    },
};
</script>
