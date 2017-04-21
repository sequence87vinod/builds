export JAVA_HOME=/var/java/jdk1.7.0_67
export PATH=${PATH}:${JAVA_HOME}/bin/


nohup java -jar -Xms64m -Xmx256m -XX:PermSize=16m -XX:MaxPermSize=32m -XX:+PrintFlagsFinal -XX:+UseStringCache -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+UseCompressedOops -XX:NewSize=32m -XX:MaxNewSize=98m -XX:+PrintTenuringDistribution -XX:+HeapDumpOnOutOfMemoryError -XX:HeapDumpPath=/mnt/appedo/logs/mps -XX:+PrintStringTableStatistics -Xloggc:/mnt/appedo/logs/print_gc.log -XX:-UseGCLogFileRotation -XX:GCLogFileSize=1m -XX:SurvivorRatio=8 log_processing_3.0.003.jar > logs/appedo_log_processing_service_3.0.003_$(date +%Y-%m-%d_%H%M).log 2>&1 &

