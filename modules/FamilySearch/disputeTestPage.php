<?php print_header("Dispute a Record"); ?>
<form action="http://localhost:81/pgv/module.php?mod=FamilySearch&pgvaction=comparePersons" method="post">
<p>PersonID: <input type="text" name="fsPerson" /></p>
<p>PGVPersonID: <input type="text" name="pgvPerson" /></p>
<p><input type="submit" /></p>
		
	