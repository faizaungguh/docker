# Command Docker

## Mongodb

### command

```bash
sudo docker up -d
```

### compose
```yml
version: '3.8' # Versi Docker Compose yang digunakan

services:
  mongodb: # Nama layanan Anda (bisa diganti)
    image: mongo # Menggunakan image resmi MongoDB
    container_name: mongodb # Nama spesifik untuk container ini
    ports:
      - "27017:27017" # Memetakan port host 27017 ke port container 27017
    volumes:
      - mongo-data:/data/db # Memetakan volume bernama 'mongo-data' ke direktori data MongoDB
    restart: always # Memastikan container akan otomatis restart jika berhenti

volumes:
  mongo-data: # Mendefinisikan volume bernama 'mongo-data'
```

## MySQL

## command
```bash
docker run --name my-mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -p 3333:3306 -d mysql:latest
```
