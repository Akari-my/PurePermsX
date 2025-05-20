# 🔐 PurePermsX

> A clean, modern and extensible permission system for PocketMine-MP (API 5+)

**PurePermsX** is a other version of the original PurePerms. completely written from scratch, Designed for modern servers, it offers powerful permission/group management with clean architecture, full API support, and seamless integration.

---

## ✨ Features

- 📦 Group and permission system
- 🔁 Add/remove permissions dynamically
- 🔗 Full integration-ready API for other plugins
- ♻️ Live config reload
- 💬 Fully customizable messages via `messages.yml`
- ✅ PocketMine-MP API 5 compatible

---

## 📁 Plugin Structure (highlights)

```
src/
└── Mellooh/
    └── PurePermsX/
        ├── commands/
        │   └── args/            # All sub-commands (e.g. group add/rm/set...)
        ├── handler/             # Permission logic and application
        ├── listener/            # Event hooks and game integration
        ├── manager/             # Data management (groups/users)
        ├── model/               # Group and User models
        ├── utils/               # Config file utils and messages
        └── PPX.php              # Main plugin class
```

---

## 🔧 Commands

Here are some of the available commands:

| Command | Description |
|--------|-------------|
| `/ppx help` | Shows all available commands |
| `/ppx group add <name>` | Create a new group |
| `/ppx group del <name>` | Delete a group |
| `/ppx user setgroup <name group>` | Assign a group to a player |
| `/ppx group addperm <group> <permission>` | Add a permission to a group |
| `/ppx group rmperm <group> <permission>` | Remove a permission from a group |
| `/ppx group perms <group>` | List group permissions |
| `/ppx list` | Show all groups |

_All messages and usage prompts are customizable inside `messages.yml`._

## 🧩 For Developers

Use `PPX::getInstance()` to access:

- `GroupManager` – for managing groups, permissions, inheritance
- `UserManager` – to get/set player group
- `PermissionHandler` – apply permissions on player join

Example:
```php
$group = PPX::getInstance()->getUserManager()->getGroup("Steve");
PPX::getInstance()->getPermissionHandler()->applyPermissions($player);
```

## 🧑‍💻 Author & License

- 📌 Plugin by [Mellooh](https://github.com/Akari-my)
- 🔓 Licensed under [MIT License](./LICENSE)

---

## ⭐ Contribute

- Found a bug? Open an issue
- Pull requests welcome
- Star the repo if you like the project!
