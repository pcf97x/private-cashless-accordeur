# Audit technique - L'Accordeur
## Lancement prevu : 14 septembre 2026

---

## BUGS CRITIQUES (a corriger avant le 14/09)

### 1. Webhook Stripe ne fonctionne pas
- `ReservationController@pay()` ne passe pas `metadata.reservation_id` dans la session Stripe
- `StripeWebhookController@handle()` cherche `$session->metadata->reservation_id` qui est toujours null
- **Impact** : si le client ferme l'onglet apres le paiement Stripe, la reservation reste "pending" alors que l'argent a ete debite
- **Fichiers** : `app/Http/Controllers/ReservationController.php:191`, `app/Http/Controllers/StripeWebhookController.php`

### 2. Route `/payment-intent` pointe vers une methode inexistante
- `ReservationController::createPaymentIntent` n'existe pas
- **Fichier** : `routes/web.php:142`

### 3. Route `/admin/rates` accessible sans authentification
- Les routes lignes 102-103 de `routes/web.php` declarent `admin/rates` GET et POST sans middleware `auth`
- N'importe qui peut voir et modifier les tarifs
- **Fichier** : `routes/web.php:102-103`

### 4. Routes dupliquees
- `dashboard` defini 3 fois (lignes 28, 32, 36)
- `admin.contacts.index` defini 2 fois (lignes 70, 77)
- `admin.reservations.index` defini 2 fois (lignes 87, 151)
- `reservation.price` defini 2 fois (lignes 117-118), la premiere pointe vers `calculatePrice` qui n'existe pas
- **Fichier** : `routes/web.php`

### 5. SSL desactive en production pour les appels Weezevent
- `WeezeventCheckinService` : `'verify' => false`
- `WeezeventParticipantService` : `->withoutVerifying()`
- Ces options ne sont pas conditionnees a l'environnement local
- **Fichiers** : `app/Services/WeezeventCheckinService.php`, `app/Services/WeezeventParticipantService.php`

### 6. CSRF non exempte pour le webhook Stripe
- La route `/stripe/webhook` recoit des POST de Stripe mais le middleware CSRF peut les bloquer avec une erreur 419
- **Fichier** : `routes/web.php:129`

---

## BUGS MODERES

### 7. Annulation reservation ne change pas le statut
- `ReservationController@cancel()` affiche juste la vue sans mettre le statut a "cancelled"
- Les creneaux restent bloques par des reservations "pending" abandonnees
- **Fichier** : `app/Http/Controllers/ReservationController.php:300-304`

### 8. Formulaire de contact ne fait rien
- `PublicController@contactStore()` valide puis redirige avec un message "envoye" mais n'envoie aucun email et ne stocke rien en base
- **Fichier** : `app/Http/Controllers/PublicController.php:48-52`

### 9. Email de confirmation reservation minimaliste
- `ReservationConfirmed` est un email HTML basique sans branding ni QR code
- Compare avec `AccessConfirmed` qui est complet et professionnel
- **Fichier** : `resources/views/emails/reservation-confirmed.blade.php`

### 10. Pas d'email envoye lors d'une annulation/remboursement admin
- `ReservationAdminController@cancelAndRefund()` annule et rembourse mais n'informe pas le client par email
- **Fichier** : `app/Http/Controllers/admin/ReservationAdminController.php`

### 11. Page success reservation accessible sans paiement
- `/reservation/success/{id}` sans `?session_id` affiche la page de succes pour n'importe quelle reservation
- **Fichier** : `app/Http/Controllers/ReservationController.php:216`

---

## BUGS MINEURS

### 12. Fichiers orphelins
- `resources/views/acces/form.blade.php` (ancien template)
- `resources/views/access/create.blade.php` (jamais utilise)
- `resources/views/checkins/confirm.blade.php` (jamais utilise)
- `resources/views/checkins/create.blade.php` (jamais utilise)
- `app/Services/WeezeventService.php` (dead code, `validateTicket()` jamais appele)

### 13. Pas de pagination sur les checkins
- `CheckinController@index()` fait `->get()` sur tous les checkins
- Risque de performance quand le volume augmente

### 14. Timezone non configure
- `APP_TIMEZONE` n'est pas defini, le serveur utilise UTC par defaut
- Les heures de pointage peuvent etre decalees de 3h par rapport a Cayenne (UTC-3)
- La commande `SyncWeezeventCheckins` corrige manuellement en `America/Cayenne` mais le reste de l'app ne le fait pas

### 15. `cron.bat` pointe vers `backend/` (ancien chemin)
- Le fichier `cron.bat` reference `backend/` qui est l'ancienne structure

---

## FONCTIONNALITES MANQUANTES

| # | Fonctionnalite | Priorite | Effort |
|---|---------------|----------|--------|
| 1 | Formulaire de contact fonctionnel (envoi email) | Haute | 30 min |
| 2 | Email de confirmation reservation avec branding | Moyenne | 1h |
| 3 | Email au client lors d'annulation/remboursement | Haute | 30 min |
| 4 | Nettoyage des reservations "pending" expirees | Moyenne | 1h |
| 5 | Dashboard avec stats revenus/reservations | Basse | 2h |
| 6 | Pagination des checkins | Basse | 30 min |
| 7 | Configuration timezone America/Cayenne | Haute | 15 min |

---

## PLAN DE CORRECTION PRIORITAIRE (avant le 14/09)

### Jour 1 (11/09) - Critiques
- [ ] Fix webhook Stripe (metadata + post-payment logic)
- [ ] Supprimer route `/payment-intent` morte
- [ ] Proteger `/admin/rates` avec auth middleware
- [ ] Nettoyer les routes dupliquees
- [ ] Conditionner SSL verify a l'environnement
- [ ] Exempter CSRF pour webhook Stripe
- [ ] Configurer APP_TIMEZONE=America/Cayenne

### Jour 2 (12/09) - Moderes
- [ ] Implementer envoi email contact
- [ ] Statut "cancelled" sur annulation
- [ ] Email au client lors d'annulation
- [ ] Ameliorer email de confirmation reservation
- [ ] Proteger page success

### Jour 3 (13/09) - Nettoyage et tests
- [ ] Supprimer fichiers orphelins
- [ ] Supprimer dead code WeezeventService
- [ ] Ajouter pagination checkins
- [ ] Tests manuels complets (reservation, paiement, pointage)
- [ ] Verification production

---

*Audit realise le 10 septembre 2026*
