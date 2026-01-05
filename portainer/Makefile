CADDY_CONTAINER = caddy

# Menjalankan Stack (Portainer + Caddy)
up:
	sudo docker compose up -d

# Mematikan Stack
down:
	sudo docker compose down

# Hot Reload Caddy (Gunakan setiap kali edit Caddyfile)
reload:
	sudo docker exec -it $(CADDY_CONTAINER) caddy reload --config /etc/caddy/Caddyfile

# Melihat Logs Caddy (Penting untuk debug domain lokal)
logs-caddy:
	sudo docker logs -f $(CADDY_CONTAINER)

# Melihat status container di stack ini
ps:
	sudo docker compose ps