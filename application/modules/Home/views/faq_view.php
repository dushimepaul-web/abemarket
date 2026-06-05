<!-- faq_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Foire Aux Questions (FAQ)</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">FAQ</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- FAQ Section Start -->
<section class="faq-section section-t-space section-b-space">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <div class="col-lg-4">
                <div class="faq-sidebar-box">
                    <div class="faq-title-box">
                        <h3>Comment pouvons-nous vous aider ?</h3>
                        <p>Trouvez rapidement les réponses à vos questions les plus fréquentes sur nos produits, commandes, livraisons et plus encore.</p>
                    </div>
                    <div class="faq-contact-box">
                        <h4>Vous n'avez pas trouvé votre réponse ?</h4>
                        <p>Contactez notre équipe support, nous sommes là pour vous aider !</p>
                        <div class="faq-contact-info">
                            <div class="info-item">
                                <i class="ri-mail-line"></i>
                                <span><?= $settings['site_email'] ?? 'contact@abemarket.com'; ?></span>
                            </div>
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <span><?= $settings['site_phone'] ?? '+257 68 86 39 45'; ?></span>
                            </div>
                            <div class="info-item">
                                <i class="ri-whatsapp-line"></i>
                                <span><?= $settings['site_whatsapp'] ?? '+257 68 86 39 45'; ?></span>
                            </div>
                        </div>
                        <a href="<?= base_url('contact'); ?>" class="btn theme-bg-color text-white w-100 mt-3">Contactez-nous</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="accordion theme-accordion" id="accordionExample">
                    <!-- Catégorie : Commandes -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrders" aria-expanded="true">
                                <i class="ri-shopping-bag-line me-2"></i> Commandes
                            </button>
                        </h2>
                        <div id="collapseOrders" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionOrders">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapse1">
                                                    Comment passer une commande sur ABEMARKET ?
                                                </button>
                                            </h2>
                                            <div id="subCollapse1" class="accordion-collapse collapse" data-bs-parent="#subAccordionOrders">
                                                <div class="accordion-body">
                                                    Pour passer une commande, suivez ces étapes simples :<br>
                                                    1. Parcourez notre catalogue et trouvez le produit qui vous intéresse.<br>
                                                    2. Cliquez sur "Ajouter au panier".<br>
                                                    3. Une fois tous vos produits ajoutés, cliquez sur l'icône du panier puis sur "Valider la commande".<br>
                                                    4. Remplissez vos informations de livraison et choisissez votre mode de paiement.<br>
                                                    5. Confirmez votre commande. Vous recevrez un email de confirmation.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapse2">
                                                Puis-je modifier ou annuler ma commande après l'avoir passée ?
                                                </button>
                                            </h2>
                                            <div id="subCollapse2" class="accordion-collapse collapse" data-bs-parent="#subAccordionOrders">
                                                <div class="accordion-body">
                                                    Vous pouvez modifier ou annuler votre commande tant qu'elle n'a pas encore été préparée par le vendeur. Connectez-vous à votre compte, allez dans "Mes commandes" et cliquez sur "Annuler" ou "Modifier". Si la commande est déjà en préparation, veuillez contacter notre service client rapidement.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapse3">
                                                Comment suivre ma commande ?
                                                </button>
                                            </h2>
                                            <div id="subCollapse3" class="accordion-collapse collapse" data-bs-parent="#subAccordionOrders">
                                                <div class="accordion-body">
                                                    Une fois votre commande expédiée, vous recevrez un email avec un numéro de suivi. Vous pouvez également suivre votre commande depuis votre espace client dans la section "Mes commandes" ou sur notre page dédiée au <a href="<?= base_url('order-tracking'); ?>">suivi de commande</a>.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie : Paiements -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayments">
                                <i class="ri-bank-card-line me-2"></i> Paiements
                            </button>
                        </h2>
                        <div id="collapsePayments" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionPayments">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subPayCollapse1">
                                                Quels modes de paiement sont acceptés ?
                                                </button>
                                            </h2>
                                            <div id="subPayCollapse1" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Nous acceptons plusieurs modes de paiement pour votre confort :<br>
                                                    - Mobile Money : <strong>Bancobu Inoti, Lumicash, EcoCash</strong><br>
                                                    - Cartes bancaires (Visa, Mastercard) via notre partenaire sécurisé<br>
                                                    - Paiement à la livraison (espèces)<br>
                                                    - Virement bancaire pour les commandes professionnelles
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subPayCollapse2">
                                                Est-ce que le paiement en ligne est sécurisé ?
                                                </button>
                                            </h2>
                                            <div id="subPayCollapse2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Oui, absolument. Tous les paiements en ligne sur ABEMARKET sont cryptés et traités via des plateformes de paiement sécurisées certifiées PCI DSS. Vos informations bancaires ne sont jamais stockées sur nos serveurs.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subPayCollapse3">
                                                Que faire en cas d'échec de paiement ?
                                                </button>
                                            </h2>
                                            <div id="subPayCollapse3" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Si votre paiement échoue, vérifiez d'abord votre solde ou les informations saisies. Vous pouvez réessayer avec un autre mode de paiement. Si le problème persiste, contactez notre support client ou votre institution financière.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie : Livraisons -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDelivery">
                                <i class="ri-truck-line me-2"></i> Livraisons
                            </button>
                        </h2>
                        <div id="collapseDelivery" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionDelivery">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subDelCollapse1">
                                                Quels sont les délais et frais de livraison ?
                                                </button>
                                            </h2>
                                            <div id="subDelCollapse1" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Les délais de livraison varient selon votre localisation :<br>
                                                    - <strong>Bujumbura Mairie</strong> : 24h à 48h (2 000 BIF)<br>
                                                    - <strong>Provinces</strong> : 3 à 5 jours ouvrés (3 000 BIF à 5 000 BIF)<br>
                                                    La livraison est <strong>gratuite</strong> pour toute commande supérieure à 150 000 BIF.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subDelCollapse2">
                                                Comment se passe la réception de ma commande ?
                                                </button>
                                            </h2>
                                            <div id="subDelCollapse2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Le livreur vous contactera avant la livraison. Pour les commandes avec paiement à la livraison, vous payez en espèces au moment de la réception. Nous vous conseillons de vérifier l'état du colis avant de signer le bon de livraison.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subDelCollapse3">
                                                Puis-je être livré dans une autre ville que la mienne ?
                                                </button>
                                            </h2>
                                            <div id="subDelCollapse3" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Oui, nous livrons sur l'ensemble des provinces du Burundi via nos transporteurs partenaires. Les frais et délais varient selon la destination. Vous pouvez également choisir de retirer votre commande dans l'un de nos points relais partenaires si cela vous est plus pratique.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie : Retours & Remboursements -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReturns">
                                <i class="ri-arrow-go-back-line me-2"></i> Retours & Remboursements
                            </button>
                        </h2>
                        <div id="collapseReturns" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionReturns">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subRetCollapse1">
                                                Quelle est la politique de retour ?
                                                </button>
                                            </h2>
                                            <div id="subRetCollapse1" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Vous disposez de <strong>14 jours</strong> à compter de la réception de votre commande pour retourner un article qui ne vous satisfait pas, à condition qu'il soit dans son état d'origine (neuf, non utilisé, avec étiquettes). Certains produits (alimentaires, hygiène, articles personnalisés) ne sont pas éligibles au retour.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subRetCollapse2">
                                                Comment demander un retour ou un remboursement ?
                                                </button>
                                            </h2>
                                            <div id="subRetCollapse2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Connectez-vous à votre compte, allez dans "Mes commandes", sélectionnez la commande concernée puis cliquez sur "Demander un retour". Remplissez le formulaire en précisant le motif. Notre équipe traitera votre demande sous 48h et vous enverra les instructions.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subRetCollapse3">
                                                Quel est le délai pour être remboursé ?
                                                </button>
                                            </h2>
                                            <div id="subRetCollapse3" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Une fois votre retour reçu et vérifié (sous 48h), le remboursement est effectué sous 5 à 10 jours ouvrés selon votre moyen de paiement d'origine (Mobile Money, carte bancaire, ou virement).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie : Compte & Sécurité -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccount">
                                <i class="ri-user-settings-line me-2"></i> Compte & Sécurité
                            </button>
                        </h2>
                        <div id="collapseAccount" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionAccount">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subAccCollapse1">
                                                Comment créer un compte client ?
                                                </button>
                                            </h2>
                                            <div id="subAccCollapse1" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Cliquez sur l'icône "Compte" en haut à droite, puis sur "S'inscrire". Remplissez le formulaire avec vos informations (nom, email, téléphone, mot de passe). Vous recevrez un code de validation par SMS ou email pour activer votre compte.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subAccCollapse2">
                                                J'ai oublié mon mot de passe, que faire ?
                                                </button>
                                            </h2>
                                            <div id="subAccCollapse2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Sur la page de connexion, cliquez sur "Mot de passe oublié". Saisissez votre adresse email, vous recevrez un lien pour réinitialiser votre mot de passe en toute sécurité.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subAccCollapse3">
                                                Comment modifier mes informations personnelles ?
                                                </button>
                                            </h2>
                                            <div id="subAccCollapse3" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Connectez-vous à votre compte, allez dans "Mon profil". Vous pourrez y modifier vos informations personnelles, votre mot de passe, vos adresses de livraison et vos préférences de notification.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie : Vendre sur ABEMARKET -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeller">
                                <i class="ri-store-2-line me-2"></i> Vendre sur ABEMARKET
                            </button>
                        </h2>
                        <div id="collapseSeller" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="faq-sub-accordion">
                                    <div class="accordion" id="subAccordionSeller">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subSelCollapse1">
                                                Comment devenir vendeur sur ABEMARKET ?
                                                </button>
                                            </h2>
                                            <div id="subSelCollapse1" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    Rendez-vous sur notre page <a href="<?= base_url('sellers'); ?>">"Vendeurs"</a> et cliquez sur "Devenir vendeur". Remplissez le formulaire d'inscription, fournissez les documents requis (pièce d'identité, attestation de commerce, NIF). Notre équipe validera votre dossier sous 48h.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subSelCollapse2">
                                                Quels sont les frais et commissions ?
                                                </button>
                                            </h2>
                                            <div id="subSelCollapse2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    La commission de base est de <strong>10%</strong> sur chaque vente réalisée. Cette commission peut varier selon la catégorie de produit (de 5% à 15%). L'inscription est gratuite. Les revenus vous sont reversés chaque mois sous déduction des éventuels frais de transaction.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subSelCollapse3">
                                                Comment sont gérés les retours pour les vendeurs ?
                                                </button>
                                            </h2>
                                            <div id="subSelCollapse3" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    En cas de retour validé, le montant correspondant est déduit de vos prochains revenus. La plateforme gère la logistique du retour. Les vendeurs sont informés à chaque étape et peuvent contester une décision via l'espace "Litiges" si nécessaire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FAQ Section End -->