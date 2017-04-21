export JAVA_HOME=/var/java/jdk1.7.0_67
export PATH=${PATH}:${JAVA_HOME}/bin/

nohup java -jar -Xms128m -Xmx256m -XX:PermSize=32m -XX:MaxPermSize=64m -XX:+PrintFlagsFinal -XX:+UseStringCache -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+UseCompressedOops -XX:NewSize=64m -XX:MaxNewSize=128m -XX:+PrintTenuringDistribution -XX:+HeapDumpOnOutOfMemoryError -XX:HeapDumpPath=/mnt/appedo/logs/mps -XX:+PrintStringTableStatistics -Xloggc:/mnt/appedo/logs/print_gc.log -XX:-UseGCLogFileRotation -XX:GCLogFileSize=1m -XX:SurvivorRatio=8 ltprocessing_3.0.019.jar > logs/appedo_lt_processing_services_3.0.019_$(date +%Y-%m-%d_%H%M).log 2>&1 &

