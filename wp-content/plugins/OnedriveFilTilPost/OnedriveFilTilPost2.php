<?php

	require dirname(__DIR__, 3) . '\vendor\autoload.php';

	require_once("../../../wp-load.php");

	use thiagoalessio\TesseractOCR\TesseractOCR;
	
	use Spatie\PdfToText\Pdf;

if(isset($_POST["LagreFil"])){
	
	LagreFil();
	
}

if(isset($_POST["BildeTilTekst"])){
	
	BildeTilTekst();
	
}

if(isset($_POST["LagreOgPubliser"])){
	
	LagreOgPubliser();
	
}

function LagreFil(){
	$alleFilnavn = "";
	
	foreach($_FILES as $fil){
		
		if(!($alleFilnavn === "")){
			$alleFilnavn .= ",";
		}
		$filnavn = $fil["name"];
		$nyttFilnavn = "bilder/" . $filnavn;
	
		$tempNavn = $fil["tmp_name"];
	
		move_uploaded_file($tempNavn, $nyttFilnavn) or die ("klarte ikke å lagre filen");
		$alleFilnavn .= "$nyttFilnavn";
	}
	echo $alleFilnavn;
}

function BildeTilTekst(){
	
	
	
	
	//$outputNyttFilnavn = substr($filnavn, 0, (strrpos($filnavn, ".")));
	//$outputFilnavn = $outputNyttFilnavn . ".html";

	//skill filnavn
	$filnavn = explode(",", $_POST["BildeTilTekst"]);
	
	//Hent tesseract.exe filepath
	$tesseractExe = get_option("Tesseract_File_Path");
	
	for($i = 0; $i < count($filnavn); $i++){
		
		$filType = pathinfo($filnavn[$i], PATHINFO_EXTENSION);
		$tillateBildetyper = array("gif", "jpg", "jpeg", "png", "bmp", "pnm", "jfif", "tiff");
		
		if(in_array($filType, $tillateBildetyper)){
		
		$ocr = (new TesseractOCR($filnavn[$i]))
		->lang("nor")
		->executable($tesseractExe)
		//->setOutputFile($outputFilnavn)
		->preserve_interword_spaces(1)
		//->setOutputFile("C:/Users/andre/Xampp/htdocs/wordpress/test.pdf")
		//->configFile("pdf")
		//->textonly_pdf(1)
		->run();
		echo "<div id='sideInfo$i'>";
		$bildeURL = "http://localhost/wordpress/wp-content/plugins/OnedriveFilTilPost/" . $filnavn[$i];
		echo "<img class='vedlegg' data-vedleggurl='$filnavn[$i]' src = '$bildeURL' > <br/>";
		
		$ocr = nl2br($ocr);
		$ocr = "<p class = 'tekst'>$ocr</p>";
		echo $ocr;
		//skille for javascript
		echo "</div>";
		} elseif($filType == "pdf"){
		
		$tekstPDF = (new Pdf("C:\Program Files\Git\mingw64\bin\pdftotext.exe"))
			->setPdf("$filnavn[$i]")
			->setOptions(["layout", "enc UTF-8"])
			->text();
		
		$tekstPDF = nl2br($tekstPDF);
		
		echo "<div id = 'sideInfo$i'>";
		echo "<div class='vedlegg' data-vedleggurl='$filnavn[$i]'> </div>";
		echo"<p class = 'tekst'>$tekstPDF</p>";
		
		echo "</div>";
		
		
		
	}else{echo "<div id='error'>Filtypen $filType er ikke støttet! </div>";}
	}
	
	
	
}

function LagreOgPubliser(){
	$tittel = $_POST["tittel"];
	
	$postArr["post_title"] = "$tittel";
	
	$kategorier = [];
	$kategorier[0] = 2;
	
	$postArr["post_category"] = $kategorier;
	
	
	$info = $_POST["LagreOgPubliser"];
	$bilder = explode(",", $_POST["bilderURL"]);
	
	//vet ikke om jeg trenger bilde id lenger, men er redd for å røre det ;(
	$bilderID = "";
	for($i = 0; $i < count($bilder); $i++){
		
		$bilderID .= strval(10 + $i);
		if($i !== count($bilder)){
			$bilderID .= ",";
		}
	}
	for($i = 0; $i < count($bilder); $i++){
		$bilde = $bilder[$i];
		$bilde = "/wordpress/wp-content/plugins/OnedriveFilTilPost/" . $bilde;
		$tillateBildetyper = array("gif", "jpg", "jpeg", "png", "bmp", "pnm", "jfif", "tiff");
		$filType = pathinfo($bilder[$i], PATHINFO_EXTENSION);
		if(in_array($filType, $tillateBildetyper)){
		$postContent .= '
		<!-- wp:image {"sizeSlug":"full"} -->
			<figure class="wp-block-image size-full">
				<img src=" ' . $bilde . '" alt="" />
			</figure>
		<!-- /wp:image -->
		';
		
	} elseif($filType == "pdf"){
		
		$fil = $bilder[$i];
		$fil = "/wordpress/wp-content/plugins/OnedriveFilTilPost/" . $fil;
		$postContent .= '
		<!-- wp:pdfemb/pdf-embedder-viewer {"pdfID":33,"url":"' . $fil . '"} -->
		<p class="wp-block-pdfemb-pdf-embedder-viewer"></p>
		<!-- /wp:pdfemb/pdf-embedder-viewer -->
		';
		
		
	}
	}
	//Gamle greia som lager galleri
	/* 	$postContent = '
	<!-- wp:gallery {"ids":[],"columns":1,"linkTo":"none","className":"alignfull columns-1"} -->
	<figure class="wp-block-gallery columns-1 is-cropped alignfull">
	<ul class="blocks-gallery-grid">
	';
	for($i = 0; $i < count($bilder); $i++){
		$bilde = $bilder[$i];
		$bildeID = strval(10 + $i);
		
		$postContent .= "
		<li class='blocks-gallery-item'>
		<figure>
		<img src='$bilde' alt='' data-id='$bildeID' data-full-url='$bilde' class='wp-image-$bildeID'/>
		</figure>
		</li>
		";
		
	}
	$postContent .= "
	</ul>
	</figure>
	<!-- /wp:gallery -->
	"; */
	
	$postContent .= "<!-- wp:paragraph --> <p style='font-size: 1px; display: none;'>" . $info . "</p> <!-- /wp:paragraph -->";
	//$postArr["post_status"] = "publish";
	
	$postArr["post_content"] = $postContent;
	
	$postID = wp_insert_post($postArr) or die ("Kunne ikke lage post");
	
 	$link = "$postID";
/* 	print_r($bilder);
	echo $filType;
	echo $postContent; */
	echo $link;
	
	/*wp_redirect($link);
	
	exit; */
}

?>