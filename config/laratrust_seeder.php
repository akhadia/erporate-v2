<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'kategori' => 'c,r,u,d',
            'produk' => 'c,r,u,d',
            'meja' => 'c,r,u,d',
            'pesanan' => 'c,r,u,d',
            'pembayaran' => 'c,r,u,d',
            'laporan' => 'c,r,u,d',

        ],
        'kasir' => [
            'pesanan' => 'r,u',
            'pembayaran' => 'c,r,u',
            
        ],
        'pelayan' => [
            'produk' => 'c,r,u,d',
            'pesanan' => 'c,r,u,d',
            'laporan' => 'c,r,u,d',
        ],
        // 'administrator' => [
        //     'users' => 'c,r,u,d',
        //     // 'profile' => 'r,u'
        // ],
        // 'user' => [
        //     // 'profile' => 'r,u'
        // ],
    ],
    'permission_structure' => [
        // 'cru_user' => [
        //     'profile' => 'c,r,u'
        // ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
