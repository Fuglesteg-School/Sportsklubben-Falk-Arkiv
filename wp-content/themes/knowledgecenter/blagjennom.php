<?php
/*
Template Name: Bla gjennom
*/

/**
 * @package KnowledgeCenter
 * @subpackage KnowledgeCenter
 * @since KnowledgeCenter 1.0
 */

get_header();
?>

<style>
h1 {
  font-weight: bold;
  font-size: 25px;
  margin: 20px 0 20px 0;
}

h2 {
  font-weight: bold;
  font-size: 20px;
  margin: 10px 0 10px 0;
}

.kategori {
  display: inline-block;
  background-color: #ffffff;
  padding: 10px 30px;
  -webkit-box-shadow: 2px 2px 11px 1px #878787;
  box-shadow: 2px 2px 11px 1px #878787;
  font-weight: bold;
  color: #1f1f1f;
  margin: 5px;
}

.kategori:hover {
  background-color: #e8e8e8;
}
</style>

<main id="primary" class="site-main">
  <section class="section">
    <div class="container is-max-widescreen py-4 ">
      <div class="columns is-multiline is-centered">
        <div class="column is-10-tablet is-9-desktop">
          <h1>Bla gjennom</h1>
          <h2>Kategorier</h2>
          <?php
          foreach (get_categories() as $kategori) {
            print "<a class='kategori' href='/wordpress/index.php/category/$kategori->slug/'>$kategori->name</a>";
          }
          ?>
          <h2>Dato</h2>
          <p>(Kommer snart)</p>
          <h2>Stikkordsøk</h2>
          <?php echo do_shortcode("[wpas id=1 title='']"); ?>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
get_footer(); ?>