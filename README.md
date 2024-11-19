# Yang Harus disiapkan

1. client id: {{30 length}} maksimal                                
2. client secret: {{30 lenght}} maksimal                                
3. token : https://{domain}/snap/v1.0/access-token/b2b                                
4. notif : https://{domain}/snap/v1.0/transfer-va/notify-payment-intrabank


## Client ID dan Client Secret

- Butuh database untuk menyimpan client id dan client secret agar dapat digunakan bukan hanya dari satu BANK saja.
- Apakah client ID dan client secret harus di hashing atau tidak?
- Bagaimana struktur database untuk menyimpan client id dan client secret?
- Bagaimana algoritma untuk meng-generate client id dan client secret?


## Token

- Token digenerate menggunakan algoritma apa?
- Bagaimana cara menggenerate token?
- Apakah digenerate menggunakan algoritma sendiri atau dengan menggunakan package/library yang sudah ada?

## Notif

- Bagaimana mekanisme notifikasi dari BANK ke aplikasi?
- Bagaimana mekanisme aplikasi ke SERVER Keuangan?
- Apakah aplikasi harus menyimpan data push notifikasi dari BANK atau hanya meneruskan langsung ke server keuangan?


