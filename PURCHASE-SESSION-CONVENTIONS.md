# Sessie-conventies (Purchase CRUD)

Gebaseerd op `breadcompany.sql`. Er is **geen aparte rollentabel** — alles zit in `client`:

    client.id          -> primary key
    client.isadmin      -> 'J' of 'N'  (bepaalt rol)
    client.pswrd         -> wachtwoord-hash

## Wat login.php moet zetten (niet in deze levering, wel nodig als contract):

```php
$_SESSION['clientid'] = $client['id'];
$_SESSION['userrole']  = ($client['isadmin'] === 'J') ? 'admin' : 'client';
```

## Door de purchase-bestanden gebruikte SESSION-variabelen

| Naam                      | Gevuld door              | Betekenis                                   |
|---------------------------|---------------------------|----------------------------------------------|
| `$_SESSION['userrole']`   | login.php                 | 'admin' of 'client'                          |
| `$_SESSION['clientid']`   | login.php                 | client.id van de ingelogde klant             |
| `$_SESSION['purchaseid']` | pur-crud-adding.php       | id van de lopende (nog niet afgesloten) bestelling |
| `$_SESSION['ordermessage']` / `orderror` | add/adding/upd-bestanden | eenmalige feedback-berichten          |
| `$_SESSION['pendingupdate']` | pur-crud-upd01.php     | tijdelijke opslag tussen bevestig-stap en opslaan |
| `$_SESSION['delmessage']` | pur-crud-delete.php       | feedback na verwijderen                      |

## Belangrijk: `$_SESSION['purchaseid']` wordt nooit automatisch gereset

Zodra een klant één keer besteld heeft, blijven alle volgende "Bestellen"-acties
aan dezelfde `purchase` hangen totdat de sessie eindigt (uitloggen/verlopen).
Wil je een expliciete "bestelling afronden"-knop die de SESSION opschoont,
laat het weten — dat is een kleine toevoeging aan `pur-crud-add.php`.

## Kolommen die WEL bestaan maar hier niet gebruikt worden

- `product.ingredients`, `product.allergens` — NOT NULL in de database,
  dus `pro-crud-add.php` / `pro-crud-upd.php` (niet mijn bestanden) moeten
  hier altijd een waarde voor meesturen, anders faalt de INSERT/UPDATE.
- `product.price` is `decimal(4,2)` → max 99,99. `purchaseline.price` is
  `decimal(7,2)` → geen praktische limiet.
