# Tambah DNS Lokal baru

## 1. Ubah Caddyfile - folder WSL
- ubah isi file `Caddyfile`
- ubah isinya jadi begini
```
portainer.local {
    reverse_proxy portainer:9443 {
        transport http {
            tls_insecure_skip_verify
        }
    }
}

api.tourism.local {
    reverse_proxy host.docker.internal:3000
}

Jika nanti ada wordpress di port 9000
wisata.local {
     reverse_proxy host.docker.internal:9000
}
```

## 2. Ubah hosts - folder Windows
- `C:\Windows\System32\drivers\etc\` Run As Administrator - Notepad
- `127.0.0.1  app.local` tambahkan
- Save
