<template>
    <a-layout-header :style="{ padding: '0 16px', background: 'white' }">
        <a-row>
            <a-col :span="1">
                <a-space>
                    <MenuOutlined class="trigger" @click="showHideMenu" />
                </a-space>
            </a-col>
            <a-col :span="21" style="overflow: hidden; white-space: nowrap; padding:6px auto;">

                    <h2 style="color: red; font-size: 16px; margin: 0;  text-overflow: ellipsis; overflow: hidden;">
                    <b>{{ formData.head }}</b>
                    </h2>
                </a-col>

                <!-- <a-col :span="5" style="overflow: hidden; white-space: nowrap; padding:6px auto">
                    <h2 style="color: red; font-size: 14px; margin: 0; text-overflow: ellipsis; overflow: hidden;">
                    <b>{{ formData.sub_head }}</b>
                    </h2>
                </a-col> -->


            <a-col :span="2">
                <HeaderRightIcons>
                    <a-space>
                        <!-- <template v-if="appSetting.shortcut_menus != 'bottom'">
                            <AffixButton position="top" />
                            <a-divider type="vertical" />
                        </template> -->
                        <a-dropdown
                            :placement="appSetting.rtl ? 'bottomLeft' : 'bottomRight'"
                        >
                            <a class="ant-dropdown-link" @click.prevent>
                                {{ selectedLang }}
                                <DownOutlined />
                            </a>
                            <template #overlay>
                                <a-menu>
                                    <a-menu-item
                                        v-for="lang in langs"
                                        :key="lang.key"
                                        @click="langSelected(lang.key)"
                                    >
                                        <a-space>
                                            <a-avatar
                                                shape="square"
                                                size="small"
                                                :src="lang.image_url"
                                            />
                                            {{ lang.name }}
                                        </a-space>
                                    </a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>
                        <a-divider type="vertical" />



                        <a-button
                            type="link"
                            @click="
                                () => {
                                    $router.push({
                                        name: 'admin.settings.profile.index',
                                    });
                                }
                            "
                            class="p-0"
                        >
                            <a-avatar size="small" :src="user.profile_image_url" />
                        </a-button>
                    </a-space>
                </HeaderRightIcons>
            </a-col>
        </a-row>
    </a-layout-header>
</template>

<script>
import { ref, reactive, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { MenuOutlined, DownOutlined, ScheduleOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { loadLocaleMessages } from "../i18n";
import { HeaderRightIcons } from "./style";
import common from "../../common/composable/common";
import MenuMode from "./MenuMode.vue";
import AffixButton from "./AffixButton.vue";

export default {


    components: {
        MenuOutlined,
        DownOutlined,
        ScheduleOutlined,
        HeaderRightIcons,
        MenuMode,
        AffixButton,
    },
    setup(props, { emit }) {
        const { user, appSetting, permsArray, menuCollapsed } = common();
        const store = useStore();
        const formData = ref({});

        const selectedLang = ref(store.state.auth.lang);
        const { locale, t } = useI18n();
        const themeMode = ref(window.config.theme_mode == "light" ? false : true);
        const themeModeLoading = ref(false);

        const langSelected = async (lang) => {
            store.commit("auth/updateLang", lang);
            await loadLocaleMessages(i18n, lang);

            selectedLang.value = lang;
            locale.value = lang;
        };

        onMounted(() => {

            const bannerPromise = axiosAdmin.get("banner");

            Promise.all([bannerPromise]).then(
                ([bannerPromise]) => {
                    console.log(bannerPromise.data,'sssssss');
                    formData.value = bannerPromise.data;
                    setFormData();
                }
            );



        });

        const showHideMenu = () => {
            store.commit("auth/updateMenuCollapsed", !menuCollapsed.value);
        };

        const logout = () => {
            store.dispatch("auth/logout");
        };

        const themeModeChanged = (checked) => {
            const mode = checked ? "dark" : "light";
            themeModeLoading.value = true;

            axiosAdmin
                .post("change-theme-mode", {
                    theme_mode: mode,
                })
                .then((response) => {
                    if (response.data.status == "success") {
                        window.location.reload();
                    }
                    themeModeLoading.value = false;
                });
        };

        return {
            permsArray,
            appSetting,
            logout,
            showHideMenu,
            langSelected,
            selectedLang,
            langs: computed(() => store.state.auth.allLangs),

            user,
            formData,
            themeMode,
            themeModeChanged,
            themeModeLoading,
        };
    },
};
</script>

<style lang="less">
.trigger {
    font-size: 18px;
    line-height: 64px;
    padding-top: 4px;
    cursor: pointer;
    transition: color 0.3s;
}

.trigger:hover {
    color: #1890ff;
}
</style>
