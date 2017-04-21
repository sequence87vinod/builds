
#nohup java -jar -Xms4G -Xmx10G -XX:PermSize=512m -XX:MaxPermSize=512m -XX:+PrintFlagsFinal -XX:+UseStringCache -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+UseCompressedOops -XX:NewSize=2g -XX:MaxNewSize=8g -XX:+PrintTenuringDistribution -XX:+HeapDumpOnOutOfMemoryError -XX:HeapDumpPath=/mnt/appedo/logs/mps -XX:+PrintStringTableStatistics -Xloggc:/mnt/appedo/logs/print_gc.log -XX:-UseGCLogFileRotation -XX:GCLogFileSize=1m -XX:SurvivorRatio=8 appedo_rum_processing_service_3.0.004.jar > logs/appedo_rum_processing_service_3.0.004_$(date +%Y-%m-%d_%H%iM).log 2>&1 &

JAVA_HOME=/var/java/jdk1.7.0_67
export PATH=${PATH}:${JAVA_HOME}/bin/

nohup java -jar -Xms64m -Xmx128m -XX:PermSize=16m -XX:MaxPermSize=32m -XX:+PrintFlagsFinal -XX:+UseStringCache -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+UseCompressedOops -XX:NewSize=32m -XX:MaxNewSize=98m -XX:+PrintTenuringDistribution -XX:+HeapDumpOnOutOfMemoryError -XX:HeapDumpPath=/mnt/appedo/logs/mps -XX:+PrintStringTableStatistics -Xloggc:/mnt/appedo/logs/print_gc.log -XX:-UseGCLogFileRotation -XX:GCLogFileSize=1m -XX:SurvivorRatio=8 rum_processing_3.0.006.jar > logs/appedo_rum_processing_service_3.0.006_$(date +%Y-%m-%d_%H%M).log 2>&1 &
