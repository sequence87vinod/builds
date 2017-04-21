copy "%JAVA_HOME%\jre\lib\security\cacerts" cacerts_@download_appln_name_lowercase@_agent
keytool -import -alias apm.@download_appln_name_lowercase@.com -keystore cacerts_@download_appln_name_lowercase@_agent -storepass changeit -file apm.@download_appln_name_lowercase@.com.crt -noprompt
