cp $JAVA_HOME/jre/lib/security/cacerts cacerts_@download_appln_name_lowercase@_agent
keytool -import -alias apm.@download_appln_name_lowercase@.com.2015 -keystore cacerts_@download_appln_name_lowercase@_agent -storepass changeit -file apm.@download_appln_name_lowercase@.com.crt -noprompt
