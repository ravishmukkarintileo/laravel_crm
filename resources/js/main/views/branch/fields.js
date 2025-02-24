import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "branches?fields=id,xid,name,address";
    const addEditUrl = "branches";
    const { t } = useI18n();

    const initData = {
        name: "",
        address: "",
    };

    const columns = [
        {
            title: 'Branch Name',
            dataIndex: "name",
        },
        {
            title: 'Branch Address',
            dataIndex: "address",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("branch.name")
        },
        {
            key: "address",
            value: t("branch.address")
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
    }
}

export default fields;
