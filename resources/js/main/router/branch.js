import Branch from '../views/branch/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/branch',
                component: Branch,
                name: 'admin.branch.index',
                meta: {
                    requireAuth: true,
                    menuParent: "branch",
                    menuKey: route => "branch",
                }
            }
        ]

    }
]
