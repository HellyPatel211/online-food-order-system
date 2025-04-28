<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodie Finds | Home</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to Foodie Finds</h1>
            <p>Discover the best dishes from around the world</p>
            <a href="category.php">Order Now</a>
        </div>
    </div>
    <!-- Slider -->
    <div class="slider-container">
    <div class="text-content">
            <h1>Best food for your taste</h1>
            <h2>About Our Service</h2>
            <p>An Online Food Ordering Website is a digital platform that allows customers to browse restaurant menus, place orders, and make payments online.This system enhances customer convenience by enabling food delivery and takeaway services with just a few clicks.</p>                                        
        </div>
        <div class="image-slider">
            <img src="noodle.jpg" alt="Image 1" class="slider-image active">
            <img src="dosa.jpg" alt="Image 2" class="slider-image">
            <img src="roll.avif" alt="Image 3" class="slider-image">
            <img src="pizza.png" alt="Image 4" class="slider-image">
        </div>
    </div>
</div>


    <?php include 'footer.html'; ?>

    <!-- JavaScript -->
    <script>
        
        const slider = document.querySelector('.image-slider');
        const images = Array.from(slider.querySelectorAll('.slider-image'));
        let currentIndex = 0;
        
        function showImage(index) {
            images.forEach(img => img.classList.remove('active'));
            images[index].classList.add('active');
        }
        
        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length; // Loop back to 0
            showImage(currentIndex);
        }

        
        showImage(currentIndex);
        setInterval(nextImage, 3000); // Change image every 3 seconds 

    </script>
</body>

</html>