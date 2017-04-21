<?xml version="1.0" encoding="ISO-8859-1"?>
<!-- Edited by XMLSpy� -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="//report">
    <html>
		<head>
			<title>Appedo</title>
			<link rel="shortcut icon" type="image/x-icon" href="images/appedo-16.ico" />
		</head>
      <body>
        <xsl:apply-templates select="error"/>
        <br/>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="error">
    <h4>Error</h4>

    <table border="1">
      <tr bgcolor="#9acd32">
        <th>scenarioname</th>
        <th>scriptname</th>
        <th>requestid</th>
        <th>url</th>
        <th>userid</th>
        <th>iterationid</th>
        <th>errorcode</th>
        <th>message</th>
        <th>time</th>

      </tr>
      <xsl:for-each select="val">
        <tr>
          <td>
            <xsl:value-of select="@scenarioname"/>
          </td>
          <td>
            <xsl:value-of select="@scriptname"/>
          </td>
          <td>
            <xsl:value-of select="@requestid"/>
          </td>
          <td>
            <xsl:value-of select="@url"/>
          </td>
          <td>
            <xsl:value-of select="@userid"/>
          </td>
          <td>
            <xsl:value-of select="@iterationid"/>
          </td>
          <td>
            <xsl:value-of select="@errorcode"/>
          </td>
          <td>
            <xsl:value-of select="@message"/>
          </td>
          <td>
            <xsl:value-of select="@time"/>
          </td>
        </tr>
      </xsl:for-each>
    </table>

  </xsl:template>

</xsl:stylesheet>

