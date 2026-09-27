OXO AGENCY — INSTALLATION cPanel

1. Confirmer le dossier racine du domaine oxo-agency.com dans cPanel (Document Root).
2. Faire une sauvegarde locale du site actuel avant de vider ce dossier.
3. Envoyer ce ZIP dans la racine, l’extraire puis supprimer le ZIP du serveur.
4. Vérifier que index.html, oxo-site.css, oxo-logo.png, devis.php et contact.php sont directement dans cette racine, sans sous-dossier supplémentaire.
5. Tester le site sur https://oxo-agency.com/ ainsi que la navigation sur mobile.
6. Créer et configurer contact@oxo-agency.com et devis@oxo-agency.com ; envoyer une demande test depuis chaque formulaire et vérifier la réception, y compris dans les indésirables. PHP mail() doit fonctionner sur cet hébergement. Si ce n’est pas le cas, le développeur devra configurer un envoi SMTP fiable.
7. Vérifier le HTTPS et les règles de sécurité proposées dans .htaccess. Si Apache renvoie une erreur 500, vérifier la compatibilité de .htaccess dans le journal d’erreurs et adapter la directive incompatible.
8. Vérifier les tarifs actuellement proposés dans les pages de prestations avant ouverture au public. Configurer les redirections des anciennes URL et Search Console après la bascule.

Ce paquet est un site HTML/CSS/PHP. Aucun WordPress ni base de données ne sont requis pour ces pages.
