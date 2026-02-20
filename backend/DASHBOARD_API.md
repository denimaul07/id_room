# Dashboard Financial Overview API

Endpoint: `GET /dashboard/overview`

Contoh response:
```json
{
  "total_revenue_today": 1200000,
  "pending_payment": 350000,
  "total_liability": 5000000,
  "membership_active_count": 120,
  "booking_active": 45,
  "transaction_chart": {
    "daily": [
      { "date": "2026-02-10", "total": 100000 },
      { "date": "2026-02-11", "total": 150000 }
    ],
    "weekly": [
      { "week": "2026-W06", "total": 900000 },
      { "week": "2026-W07", "total": 1200000 }
    ],
    "monthly": [
      { "month": "2026-01", "total": 4000000 },
      { "month": "2026-02", "total": 5000000 }
    ]
  }
}
```

Keterangan:
- `total_revenue_today`: Total pemasukan hari ini
- `pending_payment`: Total pembayaran tertunda
- `total_liability`: Saldo user beredar (liabilitas)
- `membership_active_count`: Jumlah membership aktif
- `booking_active`: Jumlah booking aktif
- `transaction_chart`: Data transaksi untuk grafik (harian, mingguan, bulanan)
