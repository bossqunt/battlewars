## ⚔️ Battlewarz
Battlewarz is a browser-based RPG set in a dynamic grid-based world. Players engage in real-time PVP, slay monsters, loot and equip powerful gear, and trade items through a live marketplace. With item rarities, stat-based battles, and a persistent online environment, Battlewarz delivers an old-school MMO vibe with modern mechanics.

## 🚀 Features
- Real-time grid-based movement and exploration
- Player-vs-Player (PVP) and Player-vs-Monster (PVE) combat
- Item rarity system with randomized stat rolls
- Inventory and equipment management
- Marketplace with offers, pricing, and trades
- Stamina, health, and stat-based mechanics
- Dockerized environment for easy setup and deployment

## 🧩 System Architecture
- Frontend: Vanilla JS + Bootstrap (lightweight and fast)
- Backend: PHP 8.0+, REST API
- Database: MySQL (MariaDB compatible)
- Authentication: PHP Sessions and JWT tokens
- Environment: Dockerized containers for PHP, MySQL, and Nginx/Apache

## 📦 Folder Structure

```
├── bw2/                 # Main app code (served as web root)
├── database/            # SQL schema and seed data
├── docker/              # Docker service configuration
├── .env.example         # Environment template
├── docker-compose.yml   # Docker Compose config
└── README.md            # You're here!
```

## 📈 Development Features
- ✅ Passive regeneration for stamina/health
- ✅ Real-time inventory management
- ✅ PVP with stat-based combat
- ✅ Marketplace offers and trades
- ✅ Rarity-based item drops and generation
- ✅ Ownership of grid tiles
- 🔜 Classes (e.g. Wizard, Ranger)
- 🔜 Guilds and World Events
- 🔜 Buffs, consumables, and status effects
- 🔜 Animated battles and effects
- 🔜 Live online player list

## 🪪 License
Open source under the MIT license.