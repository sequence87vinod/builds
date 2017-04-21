#nohup java -jar -Xms2G -Xmx2G -XX:PermSize=512m -XX:MaxPermSize=512m -XX:+PrintFlagsFinal -XX:+UseStringCache -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+UseCompressedOops -XX:NewSize=1g -XX:MaxNewSize=1g -XX:+PrintTenuringDistribution -XX:+HeapDumpOnOutOfMemoryError -XX:HeapDumpPath=/mnt/appedo/logs/mps -XX:+PrintStringTableStatistics -Xloggc:/mnt/appedo/logs/print_gc.log -XX:-UseGCLogFileRotation -XX:GCLogFileSize=1m -XX:SurvivorRatio=8 ltreporting_3.0.013.jar > logs/appedo_lt_reporting_services_3.0.013_$(date +%Y-%m-%d_%H%M).log 2>&1 & 

export JAVA_HOME=/var/java/jdk1.7.0_67
export PATH=${PATH}:${JAVA_HOME}/bin/

nohup java -jar ltreporting_3.0.016.jar > logs/appedo_lt_reporting_services_3.0.016_$(date +%Y-%m-%d_%H%M).log 2>&1 &
