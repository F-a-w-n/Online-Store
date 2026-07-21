<?php
// Original code by Fawn Barisic
// universal page footer
?>
    </main> <!-- end of main content -->
    
    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="/shamazon/about.php">About</a>
                <a href="/shamazon/faq.php">FAQ</a>
                <a href="/shamazon/contact.php">Contact</a>
            </div>
            <p>&copy; <?php echo date('Y'); ?> Shamazon. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- toggle mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburgerBtn');
            const navMenu = document.querySelector('.main-nav ul');
            if (hamburger && navMenu) {
                hamburger.addEventListener('click', function() {
                    navMenu.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>