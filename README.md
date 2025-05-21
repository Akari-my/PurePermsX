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

Perfetto!

Qui sotto trovi la **sezione `API for Developers`** ben scritta, con un **esempio avanzato completo** (commentato) e pronto da incollare nel tuo `README.md`.  
Ho mantenuto lo stile coerente al tuo file e migliorato leggermente la grammatica per dare un tocco più professionale 💼

---

### 🔌 API for Developers

PurePermsX exposes a clean and extensible API that allows your plugin to:

- Assign groups to players
- Create groups dynamically
- Set and remove permissions
- Apply group permissions to online players
- Access group/user managers easily

---

### 📦 Static API Access

Use the `PurePermsX::get...()` static accessors if your plugin initializes the API via dependency.

```php
use Mellooh\PurePermsX\api\PurePermsX;

$group = PurePermsX::getUserManager()->getGroup($player->getName());
PurePermsX::getPermissionHandler()->applyPermissions($player);
```

---

### 🧠 Example: Promote a Player to a Group Programmatically

Here’s a complete example of how to create a group (if it doesn't exist), assign it to a player, set permissions, and apply them:

```php
use Mellooh\PurePermsX\api\PurePermsX;
use pocketmine\player\Player;

class RankManager {

    /**
     * Promote player to a new group and apply all group permissions.
     *
     * @param Player $player
     * @param string $targetGroup
     */
    public static function promote(Player $player, string $targetGroup): void {
        $groupManager = PurePermsX::getGroupManager();
        $userManager = PurePermsX::getUserManager();
        $permissionHandler = PurePermsX::getPermissionHandler();

        // Automatically create the group if it doesn't exist
        if (!$groupManager->groupExists($targetGroup)) {
            $groupManager->createGroup($targetGroup);
            $groupManager->setPermissions($targetGroup, [
                "essentials.fly",
                "essentials.spawn",
                "chat.colored"
            ]);
        }

        // Set group for the player (saved in users/)
        $userManager->setGroup($player->getName(), $targetGroup);

        // Apply group permissions to the player
        $permissionHandler->applyPermissions($player);

        // Feedback to player
        $player->sendMessage("§aYou have been promoted to group §b" . ucfirst($targetGroup) . "§a!");
    }

    /**
     * Check if a player’s group has a specific permission.
     *
     * @param Player $player
     * @param string $permission
     * @return bool
     */
    public static function hasGroupPermission(Player $player, string $permission): bool {
        $group = PurePermsX::getUserManager()->getGroup($player->getName());
        $permissions = PurePermsX::getGroupManager()->getPermissions($group);

        return in_array($permission, $permissions);
    }
}
```

---

### ✅ Quick Reference

| Method | Description |
|--------|-------------|
| `PurePermsX::getGroupManager()` | Access group CRUD and permission methods |
| `PurePermsX::getUserManager()` | Manage player-to-group relations |
| `PurePermsX::getPermissionHandler()` | Apply permissions to online players |
| `createGroup(string $name)` | Creates a group |
| `addPermission($group, $node)` | Adds permission to a group |
| `setGroup($playerName, $group)` | Assigns a group to a player |
| `applyPermissions($player)` | Applies current permissions to a player |

## ⭐ Contribute

- Found a bug? Open an issue
- Pull requests welcome
- Star the repo if you like the project!
