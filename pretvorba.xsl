<?xml  version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="hr">
        <head>
            <meta charset="UTF-8"/>
		    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		    <link rel="stylesheet" type="text/css" href="dizajn.css"/>
		    <title>Muzeji</title>
        </head>
		
		<header>
			Muzeji u Londonu
		</header>
		
		<div class="row">
			<nav>
				<ul>
					<li><a href="index.html" >Pocetna</a>		</li>
					<li><a href="obrazac.html" >Pretraga</a>	</li>
					<li><a href="podaci.xml" >Muzeji</a> 	</li>
				</ul>
			</nav>
			
			<main>
				<table class="outputTable">
					<thead>
						<tr class="OTrow">
							<th>Ime</th>
							<th>Adresa</th>
							<th>Cijena</th>
							<th>Radno vrijeme</th>
							<th>Telefon</th>
							<th>Web stranica</th>
							<th>E-mail</th>
						</tr>
					</thead>
					<tbody>
						<xsl:for-each select="/muzeji/muzej">
							<tr>
								<td class="cell"><xsl:value-of select="@ime"/></td>
								<td class="cell"><xsl:value-of select="adresa/ulica"/><xsl:value-of select="adresa/ulica/@kbroj"/>, <xsl:value-of select="adresa/kvart"/></td>
								<td class="cell"><xsl:value-of select="cijena"/></td>
								
								<td class="cell">
								<xsl:for-each select="radnovrijeme">
									<tr><td><xsl:value-of select="@dan"/></td> <td><xsl:value-of select="."/></td></tr>
								</xsl:for-each>
								</td>
								
								<td class="cell">
								<xsl:for-each select="telefon">
									<tr><td><xsl:value-of select="."/></td> <td>  :  </td> <td><xsl:value-of select="@broj"/></td></tr>
								</xsl:for-each>
								</td>
								
								<td class="cell"><xsl:value-of select="webstranica"/></td>
								
								<td class="cell">
								<xsl:for-each select="email">
									<tr><td><xsl:value-of select="."/></td></tr>
								</xsl:for-each>
								</td>
							</tr>
						</xsl:for-each>
					</tbody>
				</table>
			</main>
		</div>
		
		
		<footer>
			Autor: Marin Seršić
		</footer>
		
        </html>
    </xsl:template>
</xsl:stylesheet>