
# Get Rib Info

Projet de test sur Laravel 8 et Vue 2


## Installation

Install my-project with npm

```bash
  git clone https://github.com/KRIIIB/GetRIBInfo.git
  cd GetRIBInfo
```
    
## Run Locally



Go to the project directory

```bash
  cd GetRIBInfo
```

Install dependencies

```bash
  npm install
```

Run Migrations

```bash
php artisan migrate
```

Import DB

```bash
php artisan db:seed
```

Start the server

```bash
  php artisan serve
```


## API Reference

### Récupérer toutes les opérations

```http
GET /api/operations
```

| Paramètre | Type     | Description              |
|-----------|----------|--------------------------|
| api_key   | string   | **Obligatoire.** Clé API |

---

### Récupérer les opérations par RIB

```http
GET /api/operations/rib/{rib_id}
```

| Paramètre | Type   | Description                     |
|-----------|--------|---------------------------------|
| rib_id    | string | **Obligatoire.** ID du RIB      |

---

### Ajouter une opération

```http
POST /api/operation
```

| Corps de requête | Type   | Description                            |
|------------------|--------|----------------------------------------|
| ...              | mixed  | **Obligatoire.** Données de l'opération |

---

### Mettre à jour une opération

```http
PUT /api/operation
```

| Corps de requête | Type   | Description                                   |
|------------------|--------|-----------------------------------------------|
| ...              | mixed  | **Obligatoire.** Données mises à jour de l'opération |

---

### Supprimer une opération

```http
DELETE /api/operation/{id}
```

| Paramètre | Type   | Description                            |
|-----------|--------|----------------------------------------|
| id        | string | **Obligatoire.** ID de l'opération à supprimer |

---

### Récupérer tous les RIBs

```http
GET /api/ribs
```

| Paramètre | Type     | Description              |
|-----------|----------|--------------------------|
| api_key   | string   | **Obligatoire.** Clé API |


