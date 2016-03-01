#progressbar {
	background: <?php echo $newdata['pfsettings_styling_pf_accent1'];?> !important;
}

#progressbar > span {
background-image: 
	   -webkit-gradient(linear, 0 0, 100% 100%, 
	      color-stop(.25, <?php echo $newdata['pfsettings_styling_pf_accent2'];?>), 
	      color-stop(.25, transparent), color-stop(.5, transparent), 
	      color-stop(.5, <?php echo $newdata['pfsettings_styling_pf_accent2'];?>), 
	      color-stop(.75, <?php echo $newdata['pfsettings_styling_pf_accent2'];?>), 
	      color-stop(.75, transparent), to(transparent)
	   ) !important;
	background-image: 
		-webkit-linear-gradient(
		  -45deg, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 25%, 
	      transparent 25%, 
	      transparent 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 75%, 
	      transparent 75%, 
	      transparent
	   ) !important;
	background-image: 
		-moz-linear-gradient(
		  -45deg, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 25%, 
	      transparent 25%, 
	      transparent 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 75%, 
	      transparent 75%, 
	      transparent
	   ) !important;
	background-image: 
		-ms-linear-gradient(
		  -45deg, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 25%, 
	      transparent 25%, 
	      transparent 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 75%, 
	      transparent 75%, 
	      transparent
	   ) !important;
	background-image: 
		-o-linear-gradient(
		  -45deg, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 25%, 
	      transparent 25%, 
	      transparent 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 50%, 
	      <?php echo $newdata['pfsettings_styling_pf_accent2'];?> 75%, 
	      transparent 75%, 
	      transparent
	   ) !important;
}


.pf-btn:hover, .pf-btn:active {
	color: <?php echo $newdata['pfsettings_styling_pf_accent2'];?> !important;
	background: <?php echo $newdata['pfsettings_styling_pf_accent1'];?> !important;
}


.pf-table tbody tr:hover{
	background-color:<?php echo $newdata['pfsettings_styling_pf_accent2'];?> !important;
}


.pf-headword{
	color:<?php echo $newdata['pfsettings_styling_pf_accent2'];?> !important;
}


.pf-btn{
	border: 3px solid <?php echo $newdata['pfsettings_styling_pf_accent1'];?> !important;
	color: <?php echo $newdata['pfsettings_styling_pf_accent1'];?> !important;
}

.pf-badge{
	background-color: <?php echo $newdata['pfsettings_styling_pf_accent1'];?> !important;
}



