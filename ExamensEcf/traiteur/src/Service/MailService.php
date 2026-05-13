<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(private MailerInterface $mailer) {}

    public function envoyerMailBienvenue(Utilisateur $utilisateur): void
    {
        $prenom = $utilisateur->getPrenom();
        $email = (new Email())
            ->from('noreply@traiteur.fr')
            ->to($utilisateur->getEmail())
            ->subject('Bienvenue chez notre Traiteur !')
            ->html("
                <h1>Bienvenue $prenom !</h1>
                <p>Votre compte a été créé avec succès.</p>
                <p>Vous pouvez dès maintenant vous connecter et découvrir nos menus.</p>
                <p>À bientôt !</p>
            ");

        $this->mailer->send($email);
    }

    public function envoyerConfirmationCommande(Commande $commande): void
    {
        $email = (new Email())
            ->from('noreply@traiteur.fr')
            ->to($commande->getUtilisateur()->getEmail())
            ->subject('Confirmation de votre commande ' . $commande->getNumeroCommande())
            ->html("
                <h1>Votre commande est confirmée !</h1>
                <p>Numéro de commande : <strong>" . $commande->getNumeroCommande() . "</strong></p>
                <p>Menu : <strong>" . $commande->getMenu()->getTitre() . "</strong></p>
                <p>Date de prestation : <strong>" . $commande->getDatePrestation()->format('d/m/Y') . "</strong></p>
                <p>Nombre de personnes : <strong>" . $commande->getNombrePersonne() . "</strong></p>
                <p>Prix total : <strong>" . $commande->getPrixMenu() . " €</strong></p>
                <p>Merci pour votre confiance !</p>
            ");

        $this->mailer->send($email);
    }

    public function envoyerMailAvisDisponible(Commande $commande): void
    {
        $email = (new Email())
            ->from('noreply@traiteur.fr')
            ->to($commande->getUtilisateur()->getEmail())
            ->subject('Donnez votre avis sur votre commande !')
            ->html("
                <h1>Votre commande est terminée !</h1>
                <p>Nous espérons que vous avez été satisfait de notre prestation.</p>
                <p>Connectez-vous à votre espace pour laisser un avis.</p>
            ");

        $this->mailer->send($email);
    }

    public function envoyerMailRetourMateriel(Commande $commande): void
    {
        $email = (new Email())
            ->from('noreply@traiteur.fr')
            ->to($commande->getUtilisateur()->getEmail())
            ->subject('Retour du matériel prêté')
            ->html("
                <h1>Retour du matériel</h1>
                <p>Bonjour " . $commande->getUtilisateur()->getPrenom() . ",</p>
                <p>Nous vous rappelons que du matériel vous a été prêté lors de votre commande 
                   <strong>" . $commande->getNumeroCommande() . "</strong>.</p>
                <p>Vous disposez de <strong>10 jours ouvrés</strong> pour le restituer.</p>
                <p>Sans restitution dans ce délai, des frais de <strong>600 €</strong> vous seront facturés 
                   conformément à nos conditions générales de vente.</p>
            ");

        $this->mailer->send($email);
    }
    public function envoyerMailContact(string $titre, string $email, string $description): void
{
    $mail = (new Email())
        ->from($email)
        ->to('contact@traiteur.fr')
        ->subject('Contact : ' . $titre)
        ->html("
            <h1>$titre</h1>
            <p><strong>De :</strong> $email</p>
            <p><strong>Message :</strong></p>
            <p>$description</p>
        ");

    $this->mailer->send($mail);
}
}