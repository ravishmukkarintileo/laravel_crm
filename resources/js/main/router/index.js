import { notification } from "ant-design-vue";
import { createRouter, createWebHistory } from 'vue-router';
import axios from "axios";
import { find, includes, remove, replace } from "lodash-es";
import store from '../store';

import AuthRoutes from './auth';
import DashboardRoutes from './dashboard';
import UserRoutes from './users';
import CampaignRoutes from './campaigns';
import ExpensesRoutes from './expenses';
import ProductsRoutes from './products';
import MessagingRoutes from './messaging';
import FormRoutes from './forms';
import LeadCallRoutes from './leadsCalls';
import SettingRoutes from './settings';
import BookingRoutes from './bookings';
import SetupAppRoutes from './setupApp';
import branch from "./branch";
import { checkUserPermission } from '../../common/scripts/functions';

const appType = window.config.app_type;
const allActiveModules = window.config.modules;

const isAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null) {
        return false;
    }

    return true;
}

const isSuperAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null || appSetting.white_label_completed == false) {
        return false;
    }

    return true;
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '',
            redirect: '/admin/login'
        },
        ...AuthRoutes,
        ...DashboardRoutes,
        ...branch,
        ...UserRoutes,
        ...SettingRoutes,
        ...CampaignRoutes,
        ...MessagingRoutes,
        ...FormRoutes,
        ...LeadCallRoutes,
        ...BookingRoutes,
        ...ExpensesRoutes,
        ...ProductsRoutes,
    ],
    scrollBehavior: () => ({ left: 0, top: 0 }),
});

// Including SuperAdmin Routes
const superadminRouteFilePath = appType == 'saas' ? 'superadmin' : '';
if (appType == 'saas') {
    const newSuperAdminRoutePromise = import(`../../${superadminRouteFilePath}/router/index.js`);
    const newsubscriptionRoutePromise = import(`../../${superadminRouteFilePath}/router/admin/index.js`);

    Promise.all([newSuperAdminRoutePromise, newsubscriptionRoutePromise]).then(
        ([newSuperAdminRoute, newsubscriptionRoute]) => {
            newSuperAdminRoute.default.forEach(route => router.addRoute(route));
            newsubscriptionRoute.default.forEach(route => router.addRoute(route));
            SetupAppRoutes.forEach(route => router.addRoute(route));
        }
    );
} else {
    SetupAppRoutes.forEach(route => router.addRoute(route));
}

function _0x2273() { var _0x2fb996 = ['Error', 'Error!', 'error', 'front/logout', '3726fBkwPg', 'Don\x27t\x20try\x20to\x20null\x20it...\x20otherwise\x20it\x20may\x20cause\x20error\x20on\x20your\x20server.', 'front/isLoggedIn', 'superadmin.setup_app.index', 'name', '16UdzoTV', 'beforeEach', 'non-saas', 'charAt', '98892TmXabr', 'front.homepage', '2991940NwEOsn', 'host', 'modules', 'commit', 'permission', 'post', 'superadmin', 'state', 'getters', 'admin', '3170fbcWrv', 'push', 'auth/logout', 'check', 'auth/updateActiveModules', 'appModule', 'url', 'auth/updateAppChecking', 'bottomRight', '1KRzCTa', 'is_main_product_valid', 'module', '1120136xZeABk', 'verify.main', '.settings.modules.index', 'app_type', 'auth', '2406348cnLgms', 'setup_app', 'then', '798610CrmFAn', 'verified_name', '6026548jVzBWK', 'dispatch', 'is_superadmin', 'requireAuth', 'requireUnauth', 'catch', 'Modules\x20Not\x20Verified', 'value', '21OEOJJd', 'Saas', 'forEach', 'meta', 'admin.dashboard.index', 'config', 'envato', 'saas', 'location', 'auth/isLoggedIn', 'admin.login', 'multiple_registration', 'superadmin.dashboard.index', 'user', 'split', 'stock', '.com/', 'admin.setup_app.index', 'length', 'codeifly', 'https://']; _0x2273 = function () { return _0x2fb996; }; return _0x2273(); } var _0x3e6a99 = _0x159a; (function (_0x588f91, _0x42e01c) { var _0x303d78 = _0x159a, _0x2fd6dd = _0x588f91(); while (!![]) { try { var _0x37f9e6 = parseInt(_0x303d78(0x187)) / 0x1 * (parseInt(_0x303d78(0x192)) / 0x2) + parseInt(_0x303d78(0x172)) / 0x3 * (parseInt(_0x303d78(0x16e)) / 0x4) + parseInt(_0x303d78(0x17e)) / 0x5 * (-parseInt(_0x303d78(0x169)) / 0x6) + parseInt(_0x303d78(0x19c)) / 0x7 * (-parseInt(_0x303d78(0x18a)) / 0x8) + -parseInt(_0x303d78(0x18f)) / 0x9 + parseInt(_0x303d78(0x174)) / 0xa + parseInt(_0x303d78(0x194)) / 0xb; if (_0x37f9e6 === _0x42e01c) break; else _0x2fd6dd['push'](_0x2fd6dd['shift']()); } catch (_0x55f876) { _0x2fd6dd['push'](_0x2fd6dd['shift']()); } } }(_0x2273, 0x4887e)); function _0x159a(_0x4ac853, _0x35d5ef) { var _0x227381 = _0x2273(); return _0x159a = function (_0x159a97, _0x541ff3) { _0x159a97 = _0x159a97 - 0x15d; var _0x20a0a3 = _0x227381[_0x159a97]; return _0x20a0a3; }, _0x159a(_0x4ac853, _0x35d5ef); } const checkLogFog = (_0x51769d, _0xb60c02, _0x4a4a5d) => { var _0x1c9579 = _0x159a, _0x1bd052 = window[_0x1c9579(0x1a1)]['app_type'] == 'non-saas' ? _0x1c9579(0x17d) : _0x1c9579(0x17a); const _0x2b00aa = _0x51769d[_0x1c9579(0x16d)][_0x1c9579(0x15e)]('.'); if (_0x2b00aa[_0x1c9579(0x162)] > 0x0 && _0x2b00aa[0x0] == _0x1c9579(0x17a)) { if (_0x51769d[_0x1c9579(0x19f)][_0x1c9579(0x197)] && store[_0x1c9579(0x17c)][_0x1c9579(0x1a5)] && store['state']['auth'][_0x1c9579(0x15d)] && !store[_0x1c9579(0x17b)][_0x1c9579(0x18e)][_0x1c9579(0x15d)]['is_superadmin']) store[_0x1c9579(0x195)](_0x1c9579(0x180)), _0x4a4a5d({ 'name': _0x1c9579(0x1a6) }); else { if (_0x51769d[_0x1c9579(0x19f)]['requireAuth'] && isSuperAdminCompanySetupCorrect() == ![] && _0x2b00aa[0x1] != _0x1c9579(0x190)) _0x4a4a5d({ 'name': _0x1c9579(0x16c) }); else { if (_0x51769d[_0x1c9579(0x19f)][_0x1c9579(0x197)] && !store[_0x1c9579(0x17c)][_0x1c9579(0x1a5)]) _0x4a4a5d({ 'name': 'admin.login' }); else _0x51769d['meta'][_0x1c9579(0x198)] && store['getters'][_0x1c9579(0x1a5)] ? _0x4a4a5d({ 'name': _0x1c9579(0x1a8) }) : _0x4a4a5d(); } } } else { if (_0x2b00aa[_0x1c9579(0x162)] > 0x0 && _0x2b00aa[0x0] == _0x1c9579(0x17d) && store[_0x1c9579(0x17b)][_0x1c9579(0x18e)] && store[_0x1c9579(0x17b)][_0x1c9579(0x18e)][_0x1c9579(0x15d)] && store[_0x1c9579(0x17b)][_0x1c9579(0x18e)][_0x1c9579(0x15d)][_0x1c9579(0x196)]) _0x4a4a5d({ 'name': _0x1c9579(0x1a8) }); else { if (_0x2b00aa[_0x1c9579(0x162)] > 0x0 && _0x2b00aa[0x0] == _0x1c9579(0x17d)) { if (_0x51769d['meta'][_0x1c9579(0x197)] && !store[_0x1c9579(0x17c)]['auth/isLoggedIn']) store[_0x1c9579(0x195)](_0x1c9579(0x180)), _0x4a4a5d({ 'name': _0x1c9579(0x1a6) }); else { if (_0x51769d[_0x1c9579(0x19f)][_0x1c9579(0x197)] && isAdminCompanySetupCorrect() == ![] && _0x2b00aa[0x1] != _0x1c9579(0x190)) _0x4a4a5d({ 'name': _0x1c9579(0x161) }); else { if (_0x51769d[_0x1c9579(0x19f)]['requireUnauth'] && store['getters'][_0x1c9579(0x1a5)]) _0x4a4a5d({ 'name': _0x1c9579(0x1a0) }); else { if (_0x51769d[_0x1c9579(0x16d)] == _0x1bd052 + _0x1c9579(0x18c)) store['commit'](_0x1c9579(0x185), ![]), _0x4a4a5d(); else { var _0x4dab42 = _0x51769d[_0x1c9579(0x19f)]['permission']; _0x2b00aa[0x1] == _0x1c9579(0x15f) && (_0x4dab42 = replace(_0x51769d[_0x1c9579(0x19f)][_0x1c9579(0x178)](_0x51769d), '-', '_')), !_0x51769d[_0x1c9579(0x19f)][_0x1c9579(0x178)] || checkUserPermission(_0x4dab42, store[_0x1c9579(0x17b)][_0x1c9579(0x18e)][_0x1c9579(0x15d)]) ? _0x4a4a5d() : _0x4a4a5d({ 'name': _0x1c9579(0x1a0) }); } } } } } else _0x2b00aa[_0x1c9579(0x162)] > 0x0 && _0x2b00aa[0x0] == 'front' ? _0x51769d['meta'][_0x1c9579(0x197)] && !store[_0x1c9579(0x17c)][_0x1c9579(0x16b)] ? (store[_0x1c9579(0x195)](_0x1c9579(0x168)), _0x4a4a5d({ 'name': _0x1c9579(0x173) })) : _0x4a4a5d() : _0x4a4a5d(); } } }; var mAry = ['e', 'L', 'a', 'r', 'd', 'P', 'o'], mainProductName = '' + mAry[0x1] + mAry[0x0] + mAry[0x2] + mAry[0x4] + mAry[0x5] + mAry[0x3] + mAry[0x6]; window['config'][_0x3e6a99(0x18d)] == 'saas' && (mainProductName += _0x3e6a99(0x19d)); var modArray = [{ 'verified_name': mainProductName, 'value': ![] }]; allActiveModules[_0x3e6a99(0x19e)](_0x3c59f3 => { var _0x363d19 = _0x3e6a99; modArray[_0x363d19(0x17f)]({ 'verified_name': _0x3c59f3, 'value': ![] }); }); const isAnyModuleNotVerified = () => { return find(modArray, ['value', ![]]); }, isCheckUrlValid = (_0x12986c, _0xe0ee80, _0x49932b) => { var _0x5c6b52 = _0x3e6a99; if (_0x12986c[_0x5c6b52(0x162)] != 0x5 || _0xe0ee80[_0x5c6b52(0x162)] != 0x8 || _0x49932b['length'] != 0x6) return ![]; else { if (_0x12986c[_0x5c6b52(0x171)](0x3) != 'c' || _0x12986c[_0x5c6b52(0x171)](0x4) != 'k' || _0x12986c[_0x5c6b52(0x171)](0x0) != 'c' || _0x12986c[_0x5c6b52(0x171)](0x1) != 'h' || _0x12986c[_0x5c6b52(0x171)](0x2) != 'e') return ![]; else { if (_0xe0ee80[_0x5c6b52(0x171)](0x2) != 'd' || _0xe0ee80[_0x5c6b52(0x171)](0x3) != 'e' || _0xe0ee80[_0x5c6b52(0x171)](0x4) != 'i' || _0xe0ee80[_0x5c6b52(0x171)](0x0) != 'c' || _0xe0ee80[_0x5c6b52(0x171)](0x1) != 'o' || _0xe0ee80['charAt'](0x5) != 'f' || _0xe0ee80['charAt'](0x6) != 'l' || _0xe0ee80[_0x5c6b52(0x171)](0x7) != 'y') return ![]; else return _0x49932b['charAt'](0x2) != 'v' || _0x49932b[_0x5c6b52(0x171)](0x3) != 'a' || _0x49932b['charAt'](0x0) != 'e' || _0x49932b['charAt'](0x1) != 'n' || _0x49932b[_0x5c6b52(0x171)](0x4) != 't' || _0x49932b[_0x5c6b52(0x171)](0x5) != 'o' ? ![] : !![]; } } }, isAxiosResponseUrlValid = _0xc16485 => { var _0x48f032 = _0x3e6a99; return _0xc16485['charAt'](0x13) != 'i' || _0xc16485['charAt'](0xd) != 'o' || _0xc16485[_0x48f032(0x171)](0x9) != 'n' || _0xc16485['charAt'](0x10) != 'o' || _0xc16485[_0x48f032(0x171)](0x16) != 'y' || _0xc16485[_0x48f032(0x171)](0xb) != 'a' || _0xc16485[_0x48f032(0x171)](0x12) != 'e' || _0xc16485[_0x48f032(0x171)](0x15) != 'l' || _0xc16485[_0x48f032(0x171)](0xa) != 'v' || _0xc16485['charAt'](0x14) != 'f' || _0xc16485[_0x48f032(0x171)](0xc) != 't' || _0xc16485[_0x48f032(0x171)](0x11) != 'd' || _0xc16485['charAt'](0x8) != 'e' || _0xc16485[_0x48f032(0x171)](0xf) != 'c' || _0xc16485[_0x48f032(0x171)](0x1a) != 'm' || _0xc16485['charAt'](0x18) != 'c' || _0xc16485['charAt'](0x19) != 'o' ? ![] : !![]; }; router[_0x3e6a99(0x16f)]((_0x35c99f, _0x3f0d3f, _0x422a9f) => { var _0x580c7b = _0x3e6a99, _0x3a3ac8 = _0x580c7b(0x1a2), _0x57e4a0 = _0x580c7b(0x163), _0x40fce5 = _0x580c7b(0x181), _0x186231 = { 'modules': window['config'][_0x580c7b(0x176)] }; _0x35c99f[_0x580c7b(0x19f)] && _0x35c99f[_0x580c7b(0x19f)][_0x580c7b(0x183)] && (_0x186231[_0x580c7b(0x189)] = _0x35c99f['meta'][_0x580c7b(0x183)], !includes(allActiveModules, _0x35c99f[_0x580c7b(0x19f)]['appModule']) && _0x422a9f({ 'name': 'admin.login' })); if (!isCheckUrlValid(_0x40fce5, _0x57e4a0, _0x3a3ac8)) Modal['error']({ 'title': _0x580c7b(0x166), 'content': 'Don\x27t\x20try\x20to\x20null\x20it...\x20otherwise\x20it\x20may\x20cause\x20error\x20on\x20your\x20server.' }); else { var _0x795d5 = window[_0x580c7b(0x1a1)][_0x580c7b(0x18d)] == _0x580c7b(0x170) ? _0x580c7b(0x17d) : _0x580c7b(0x17a); if (isAnyModuleNotVerified() !== undefined && _0x35c99f[_0x580c7b(0x16d)] && _0x35c99f['name'] != 'verify.main' && _0x35c99f[_0x580c7b(0x16d)] != _0x795d5 + _0x580c7b(0x18c)) { var _0x55c6f2 = _0x580c7b(0x164) + _0x3a3ac8 + '.' + _0x57e4a0 + _0x580c7b(0x160) + _0x40fce5; axios({ 'method': _0x580c7b(0x179), 'url': _0x55c6f2, 'data': { 'verified_name': mainProductName, ..._0x186231, 'domain': window[_0x580c7b(0x1a4)][_0x580c7b(0x175)] }, 'timeout': 0xfa0 })[_0x580c7b(0x191)](_0x182311 => { var _0x299e86 = _0x580c7b; if (!isAxiosResponseUrlValid(_0x182311['config'][_0x299e86(0x184)])) Modal['error']({ 'title': _0x299e86(0x166), 'content': _0x299e86(0x16a) }); else { store[_0x299e86(0x177)](_0x299e86(0x185), ![]); const _0x597c96 = _0x182311['data']; _0x597c96['main_product_registered'] && (modArray[_0x299e86(0x19e)](_0x1ef9f0 => { var _0x412e43 = _0x299e86; _0x1ef9f0[_0x412e43(0x193)] == mainProductName && (_0x1ef9f0[_0x412e43(0x19b)] = !![]); }), modArray[_0x299e86(0x19e)](_0x14a2a7 => { var _0xf90e7a = _0x299e86; if (includes(_0x597c96['modules_not_registered'], _0x14a2a7[_0xf90e7a(0x193)]) || includes(_0x597c96['multiple_registration_modules'], _0x14a2a7['verified_name'])) { if (_0x14a2a7[_0xf90e7a(0x193)] != mainProductName) { var _0x230d00 = [...window[_0xf90e7a(0x1a1)][_0xf90e7a(0x176)]], _0x46f0c8 = remove(_0x230d00, function (_0x66ae97) { return _0x66ae97 != _0x14a2a7['verified_name']; }); store[_0xf90e7a(0x177)](_0xf90e7a(0x182), _0x46f0c8), window['config'][_0xf90e7a(0x176)] = _0x46f0c8; } _0x14a2a7[_0xf90e7a(0x19b)] = ![]; } else _0x14a2a7[_0xf90e7a(0x19b)] = !![]; })); if (!_0x597c96[_0x299e86(0x188)]) { } else { if (!_0x597c96['main_product_registered'] || _0x597c96[_0x299e86(0x1a7)]) _0x422a9f({ 'name': _0x299e86(0x18b) }); else { if (_0x35c99f[_0x299e86(0x19f)] && _0x35c99f[_0x299e86(0x19f)][_0x299e86(0x183)] && find(modArray, { 'verified_name': _0x35c99f[_0x299e86(0x19f)][_0x299e86(0x183)], 'value': ![] }) !== undefined) { notification[_0x299e86(0x167)]({ 'placement': _0x299e86(0x186), 'message': _0x299e86(0x165), 'description': _0x299e86(0x19a) }); const _0x96b764 = appType == _0x299e86(0x1a3) ? 'superadmin' : _0x299e86(0x17d); _0x422a9f({ 'name': _0x96b764 + '.settings.modules.index' }); } else checkLogFog(_0x35c99f, _0x3f0d3f, _0x422a9f); } } } })[_0x580c7b(0x199)](_0x5275d9 => { var _0x46c66e = _0x580c7b; !isAxiosResponseUrlValid(_0x5275d9['toJSON']()['config'][_0x46c66e(0x184)]) ? Modal['error']({ 'title': _0x46c66e(0x166), 'content': 'Don\x27t\x20try\x20to\x20null\x20it...\x20otherwise\x20it\x20may\x20cause\x20error\x20on\x20your\x20server.' }) : (modArray[_0x46c66e(0x19e)](_0x4c6d15 => { var _0x41c513 = _0x46c66e; _0x4c6d15[_0x41c513(0x19b)] = !![]; }), store['commit'](_0x46c66e(0x185), ![]), _0x422a9f()); }); } else checkLogFog(_0x35c99f, _0x3f0d3f, _0x422a9f); } });

export default router
