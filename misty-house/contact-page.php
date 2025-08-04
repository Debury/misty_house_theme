<?php
/**
 * Template Name: Kontakt
 * @package Misty_House
 */

// DEBUG: This is the kontakt page template loading
get_header(); ?>

<!-- DEBUG: Kontakt template loaded at <?php echo date('Y-m-d H:i:s'); ?> -->
<div class="contact-page">
    <div class="contact-container">
        <h1 class="contact-title">Kontakt</h1>
        <p class="contact-subtitle">
            Ak máš problemy s dodaním objednávky alebo chceš Vyriešiť s nami roky merch alebo také
            také telá tiež blesk blahých blahých blah
        </p>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="contact-message success">
                ✅ Tvoja správa bola úspešne odoslaná! Ozveme sa ti čoskoro.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="contact-message error">
                <?php
                switch ($_GET['error']) {
                    case 'missing_fields':
                        echo '❌ Prosím vyplň všetky povinné polia.';
                        break;
                    case 'invalid_email':
                        echo '❌ Neplatná emailová adresa.';
                        break;
                    case 'send_failed':
                        echo '❌ Chyba pri odosielaní správy. Skús to znovu.';
                        break;
                    default:
                        echo '❌ Vyskytla sa chyba. Skús to znovu.';
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="contact-form-container">
            <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="contact_form_submit">
                <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>

                <div class="form-row">
                    <input type="text" name="first_name" placeholder="First name" required>
                    <input type="text" name="last_name" placeholder="Last name" required>
                </div>

                <div class="form-row">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="phone" placeholder="Phone number">
                </div>

                <div class="form-row full-width">
                    <textarea name="message" placeholder="Message" rows="6" required></textarea>
                </div>

                <button type="submit" class="contact-submit">Send</button>
            </form>
        </div>
    </div>

    <!-- Background decorative elements -->
    <div class="contact-bg-decoration"></div>
</div>

<style>
.contact-page {
    background-color: #000;
    color: #fff;
    min-height: 100vh;
    padding: 120px 20px 60px;
    position: relative;
    overflow: hidden;
}

.contact-container {
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.contact-title {
    color: #ffb700;
    font-family: 'Jockey One', sans-serif;
    font-size: 3.5rem;
    text-align: center;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.contact-subtitle {
    text-align: center;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 50px;
    color: #ccc;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.contact-message {
    text-align: center;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    font-weight: bold;
}

.contact-message.success {
    background: rgba(0, 255, 0, 0.1);
    border: 2px solid #00ff00;
    color: #00ff00;
}

.contact-message.error {
    background: rgba(255, 0, 0, 0.1);
    border: 2px solid #ff4444;
    color: #ff4444;
}

.contact-form-container {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 15px;
    border: 1px solid rgba(255, 183, 0, 0.2);
    backdrop-filter: blur(10px);
}

.contact-form .form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.contact-form .form-row.full-width {
    flex-direction: column;
}

.contact-form input,
.contact-form textarea {
    flex: 1;
    padding: 15px 20px;
    background: rgba(0, 0, 0, 0.7);
    border: 2px solid rgba(255, 183, 0, 0.3);
    border-radius: 8px;
    color: #fff;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s ease;
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
    color: #999;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: #ffb700;
    background: rgba(0, 0, 0, 0.9);
    box-shadow: 0 0 15px rgba(255, 183, 0, 0.2);
}

.contact-submit {
    width: 100%;
    padding: 18px 30px;
    background: linear-gradient(45deg, #ffb700, #ff8c00);
    border: none;
    border-radius: 8px;
    color: #000;
    font-size: 1.1rem;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.contact-submit:hover {
    background: linear-gradient(45deg, #ff8c00, #ffb700);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 183, 0, 0.4);
}

.contact-bg-decoration {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('<?php echo get_template_directory_uri(); ?>/assets/images/graffiti-pattern.png') repeat;
    opacity: 0.03;
    z-index: 1;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .contact-page {
        padding: 100px 15px 40px;
    }

    .contact-title {
        font-size: 2.5rem;
    }

    .contact-subtitle {
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    .contact-form-container {
        padding: 25px;
    }

    .contact-form .form-row {
        flex-direction: column;
        gap: 15px;
    }

    .contact-form input,
    .contact-form textarea {
        padding: 12px 15px;
    }
}
</style>

<?php get_footer(); ?>
