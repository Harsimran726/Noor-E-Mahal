import os
import glob

files = glob.glob('*.php') + glob.glob('admin/*.php')
for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replacing absolute root links with relative links to .php files
    content = content.replace('href="/gallery"', 'href="gallery.php"')
    content = content.replace('href="/about"', 'href="about.php"')
    content = content.replace('href="/facilities"', 'href="facilities.php"')
    content = content.replace('href="/contact"', 'href="contact.php"')
    content = content.replace('href="/services"', 'href="services.php"')
    
    # Replacing href="/" with href="index.php" but ignoring things inside the admin dashboard
    if file != 'admin\\index.php' and file != 'admin/index.php':
        content = content.replace('href="/"', 'href="index.php"')

    # Fix for login.html where it might use '../index.php' instead of 'index.php'
    if file == 'admin\\login.php' or file == 'admin/login.php':
        content = content.replace('href="index.php" class="back-link"', 'href="../index.php" class="back-link"')

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
print('Fixed links in all PHP files.')
