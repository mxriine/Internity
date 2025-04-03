<!-- Section principale avec image et formulaire, pour Discovery et Companies-->
<section class="hero-section">
    <div class="background-image">
        <img src="/assets/images/discover.jpg" alt="Image de fond">
    </div>
    <div class="search-form">
        <form id="searchForm" action="#" method="get">
            <div class="form-group">
                <label for="what">QUOI ?</label>
                <input type="text" id="what" name="search" placeholder="Métier, entreprise, compétence..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="where">OÙ ?</label>
                <input type="text" id="where" name="location" placeholder="Ville, département, code postal..." value="<?php echo htmlspecialchars($_GET['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <button type="submit" class="btn btn-search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
</section>
