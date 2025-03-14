<template>
    <a-row v-if="parsedFileData.length == 0" :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
            <a-upload-dragger
                v-model:fileList="fileList"
                name="file"
                :accept="acceptFormat"
                :customRequest="customRequest"
                @drop="customRequest"
            >
                <p class="ant-upload-drag-icon">
                    <UploadOutlined />
                </p>
                <p class="ant-upload-text">Click or drag file to this area to upload</p>
                <p class="ant-upload-hint">
                    Support for a single or bulk upload. Strictly prohibit from uploading
                    company data or other band files
                </p>
            </a-upload-dragger>
        </a-col>
    </a-row>
    <a-row v-else :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-tabs v-model:activeKey="activeTabKey" @change="tabChanged">
                        <a-tab-pane key="all">
                            <template #tab>
                                <a-space>
                                    <a-badge
                                        :count="size(formProperties)"
                                        :number-style="{
                                            backgroundColor: '#fff',
                                            color: '#999',
                                            boxShadow: '0 0 0 1px #d9d9d9 inset',
                                        }"
                                        :overflowCount="2000"
                                    />
                                    {{ $t("campaign.all_columns") }}
                                </a-space>
                            </template>
                        </a-tab-pane>

                        <a-tab-pane key="matched">
                            <template #tab>
                                <a-space>
                                    <a-badge
                                        :count="
                                            size(formProperties) - unMatchedColumnsCount
                                        "
                                        :number-style="{ backgroundColor: '#52c41a' }"
                                        :overflowCount="2000"
                                    />
                                    {{ $t("campaign.matched_columns") }}
                                </a-space>
                            </template>
                        </a-tab-pane>
                        <a-tab-pane key="un_matched">
                            <template #tab>
                                <a-space>
                                    <a-badge
                                        :count="unMatchedColumnsCount"
                                        :overflowCount="2000"
                                    />
                                    {{ $t("campaign.unmatched_columns") }}
                                </a-space>
                            </template>
                        </a-tab-pane>
                    </a-tabs>
                </a-col>
            </a-row>

            <a-table
                :columns="columns"
                :row-key="(record) => record"
                :data-source="tableRecords"
                :pagination="false"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex === 'csv_header_field'">
                        {{ record }}
                    </template>
                    <template v-if="column.dataIndex === 'csv_field_data'">
                        <span v-html="getPreviewData(record)"></span>
                    </template>
                    <template v-if="column.dataIndex === 'column_status'">
                        <a-select
                            v-model:value="formProperties[record]"
                            :placeholder="
                                $t('common.select_default_text', [$t('lead.campaign')])
                            "
                            :allowClear="true"
                            style="width: 100%"
                            optionFilterProp="title"
                            show-search
                            @change="reAssignCurrentFormFields"
                            :open="
                                formProperties[record] != undefined ? false : undefined
                            "
                        >
                            <a-select-option
                                v-for="formFields in currentFormFields"
                                :key="formFields"
                                :title="record"
                                :value="formFields"
                            >
                                {{ formFields }}
                            </a-select-option>
                        </a-select>
                        <div
                            v-show="formProperties[record] == undefined"
                            class="ant-form-item-explain-error"
                            style=""
                        >
                            {{ $t("campaign.column_will_not_imported") }}
                        </div>
                    </template>
                </template>
            </a-table>
        </a-col>
    </a-row>
</template>

<script>
import { computed, defineComponent, ref, watch } from "vue";
import { UploadOutlined, FileOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import Papa from "papaparse";
import { forEach, find, includes, size } from "lodash-es";
import * as XLSX from "xlsx";

export default defineComponent({
    props: {
        acceptFormat: {
            default: "image/*,.pdf",
            type: String,
        },
        allFields: null,
    },
    emits: ["fileUploaded", "leadColumnChanged"],
    components: {
        UploadOutlined,
        FileOutlined,
    },

    setup(props, { emit }) {
        const fileList = ref([]);
        const loading = ref(false);
        const { t } = useI18n();
        const currentUploadedFile = ref(undefined);
        const parsedFileData = ref([]);
        const tableRecords = ref([]);
        const parsedHeader = ref([]);
        const formProperties = ref({});
        const currentFormFields = ref([...props.allFields]);
        const activeTabKey = ref("all");

        const columns = [
            {
                title: t("campaign.csv_header_field"),
                dataIndex: "csv_header_field",
            },
            {
                title: t("campaign.csv_field_data"),
                dataIndex: "csv_field_data",
            },
            {
                title: t("campaign.column_status"),
                dataIndex: "column_status",
            },
        ];

        const customRequest = (info) => {
            const file = info.file;
            const fileType = file.name.split(".").pop().toLowerCase();

            loading.value = true;

            if (fileType === "csv") {
                // Handle CSV file using PapaParse
                let reader = new FileReader();
                reader.readAsText(file, "UTF-8");
                reader.onload = function (evt) {
                    parseCSV(evt.target.result);
                };
            } else if (fileType === "xlsx") {
                // Handle XLSX file
                let reader = new FileReader();
                reader.readAsArrayBuffer(file);
                reader.onload = function (evt) {
                    parseXLSX(evt.target.result);
                };
            } else {
                console.error("Unsupported file format");
            }

            emit("fileUploaded", file);
        };

        const getPreviewData = (fieldName) => {
            var fieldDataString = "";

            forEach(parsedFileData.value, (filterValue, filterKey) => {
                fieldDataString += `${filterValue[fieldName]}<br />`;
            });

            return fieldDataString;
        };

        const reAssignCurrentFormFields = () => {
            var newFormFields = [];

            forEach(props.allFields, (filterValue, filterKey) => {
                if (!includes(formProperties.value, filterValue)) {
                    newFormFields.push(filterValue);
                }
            });
            currentFormFields.value = newFormFields;

            tabChanged(activeTabKey.value);

            emit("leadColumnChanged", formProperties.value);
        };

        const unMatchedColumnsCount = computed(() => {
            var counter = 0;

            forEach(formProperties.value, (filterValue, filterKey) => {
                if (filterValue == undefined) {
                    counter = counter + 1;
                }
            });

            return counter;
        });

        const tabChanged = (activeKey) => {
            if (activeKey == "matched") {
                var newDataResult = [];

                forEach(parsedHeader.value, (filterValue, filterKey) => {
                    if (formProperties.value[filterValue] != undefined) {
                        newDataResult.push(filterValue);
                    }
                });
                tableRecords.value = newDataResult;
            } else if (activeKey == "un_matched") {
                var newDataResult = [];

                forEach(parsedHeader.value, (filterValue, filterKey) => {
                    if (formProperties.value[filterValue] == undefined) {
                        newDataResult.push(filterValue);
                    }
                });
                tableRecords.value = newDataResult;
            } else {
                tableRecords.value = parsedHeader.value;
            }
        };

        watch(
            () => props.allFields,
            (newVal, oldVal) => {
                reAssignCurrentFormFields();
            }
        );

        // Function to parse CSV file
        const parseCSV = (csvData) => {
            const parsedResult = Papa.parse(csvData, {
                preview: 3,
                header: true,
                skipEmptyLines: true,
            });

            processParsedData(parsedResult.data, parsedResult.meta.fields);
        };

        // Function to parse XLSX file and convert it to CSV
        const parseXLSX = (data) => {
            const workbook = XLSX.read(data, { type: "array" });
            const sheetName = workbook.SheetNames[0]; // Get first sheet
            const sheet = workbook.Sheets[sheetName];

            // Convert to CSV
            const csv = XLSX.utils.sheet_to_csv(sheet);
            parseCSV(csv);
        };

        // Function to process parsed data (both CSV & XLSX)
        const processParsedData = (parsedData, parsedHeaders) => {
            parsedFileData.value = parsedData;
            parsedHeader.value = parsedHeaders;

            let newValues = {};
            parsedHeaders.forEach((filterValue) => {
                const isFieldFind = props.allFields.includes(filterValue);
                newValues[filterValue] = isFieldFind ? filterValue : undefined;
            });

            formProperties.value = newValues;
            reAssignCurrentFormFields();
        };

        return {
            fileList,
            loading,
            customRequest,
            parseCSV,
            parseXLSX,
            processParsedData,
            columns,
            currentUploadedFile,
            parsedFileData,
            parsedHeader,
            getPreviewData,
            formProperties,

            currentFormFields,
            reAssignCurrentFormFields,
            unMatchedColumnsCount,

            activeTabKey,
            tabChanged,
            tableRecords,
            size,
        };
    },
});
</script>
