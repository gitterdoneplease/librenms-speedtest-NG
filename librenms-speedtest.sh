#!/bin/bash
#############################
# LibreNMS Speedtest plugin #
#############################
# Main plugin dir
SpeedtestPluginDir=/opt/librenms/html/plugins/Speedtest
# Other data dirs
RRDGraphsDir=$SpeedtestPluginDir/rrd
PNGImagesDir=$SpeedtestPluginDir/png
SpeedtestResultDir=$SpeedtestPluginDir/tmp

# Active script code

        case $1 in (create)
                # Create the Latency measurement RRD
                rrdtool create $RRDGraphsDir/speedtest-latency.rrd -s 1800 \
                DS:LATENCY:GAUGE:3600:0:U \
                RRA:AVERAGE:0.5:1:576 \
                RRA:AVERAGE:0.5:6:672 \
                RRA:AVERAGE:0.5:24:732 \
                RRA:AVERAGE:0.5:144:1460

                # Create the Bandwidth measurement RRD
                rrdtool create $RRDGraphsDir/speedtest-bandwidth.rrd -s 1800 \
                DS:DOWN:GAUGE:3600:0:U \
                DS:UP:GAUGE:3600:0:U \
                RRA:AVERAGE:0.5:1:576 \
                RRA:AVERAGE:0.5:6:672 \
                RRA:AVERAGE:0.5:24:732 \
                RRA:AVERAGE:0.5:144:1460
                ;;
        (run)
                # Get the date of the moment we start the test, in epoch format
                DATE=$(/bin/date +%s)

                # Generate speedtest results, store them in a temp file
                speedtest --accept-license --accept-gdpr -p no > $SpeedtestResultDir/speedtest-results 2>/dev/null

                # Get the Latency
                Latency=$(cat $SpeedtestResultDir/speedtest-results | grep Latency | sed 's/.*Latency:\s*\([0-9]*.[0-9]*\).*/\1/')

                # Get the Download speed
                DownloadSpeed=$(cat $SpeedtestResultDir/speedtest-results | grep Download | sed 's/.*Download:\s*\([0-9]*.[0-9]*\).*/\1/')

                # Get the Upload speed
                UploadSpeed=$(cat $SpeedtestResultDir/speedtest-results | grep Upload | sed 's/.*Upload:\s*\([0-9]*.[0-9]*\).*/\1/')

                # Get the server that was used, dump it into a file
                cat $SpeedtestResultDir/speedtest-results | grep Server | sed 's/.*Server:\s*\(.*\)/\1/' > $SpeedtestResultDir/speedtest-server
                
                # Update the RRD graphs
                rrdtool update $RRDGraphsDir/speedtest-latency.rrd $DATE:$Latency
                rrdtool update $RRDGraphsDir/speedtest-bandwidth.rrd $DATE:$DownloadSpeed:$UploadSpeed
                ;;
        (graph)
                # Generate latency graphs
                periods=(day week month year)
                titles=("Last Day" "Last Week" "Last Month" "Last Year")
                for i in "${!periods[@]}"; do
                    period=${periods[i]}
                    title=${titles[i]}
                    rrdtool graph $PNGImagesDir/speedtest-latency-${period}.png -J -a PNG --start "-1${period}" \
                        --title="$title" \
                        --vertical-label "ms" \
                        DEF:P=$RRDGraphsDir/speedtest-latency.rrd:LATENCY:AVERAGE \
                        DEF:PMIN=$RRDGraphsDir/speedtest-latency.rrd:LATENCY:MIN \
                        DEF:PMAX=$RRDGraphsDir/speedtest-latency.rrd:LATENCY:MAX \
                        VDEF:Pavg=P,AVERAGE \
                        VDEF:Plast=P,LAST \
                        LINE1:P#3d61ab:"Last latency (ms)" \
                        LINE2:Pavg#cc3300:"Avg \n" \
                        GPRINT:Plast:"Last latency\: %2.1lf ms\n" \
                        GPRINT:Pavg:"Avg latency\: %2.1lf ms" \
                        -h 600 -w 1200 -y1:2 \
                        -c BACK#EEEEEE00 -c SHADEA#EEEEEE00 -c SHADEB#EEEEEE00 -c CANVAS#FFFFFF00 \
                        -c GRID#a5a5a5 -c MGRID#FF9999 -c FRAME#5e5e5e -c ARROW#5e5e5e \
                        -R normal -c FONT#000000 --font LEGEND:8:DejaVuSansMono --font AXIS:7:DejaVuSansMono > /dev/null 2>&1
                done

                # Generate bandwidth graphs
                for i in "${!periods[@]}"; do
                    period=${periods[i]}
                    title=${titles[i]}

                    rrdtool graph "$PNGImagesDir/speedtest-bandwidth-${period}.png" -J -a PNG --start "-1${period}" \
                        --title="$title" \
                        --vertical-label "Mbit/s" \
                        DEF:D="$RRDGraphsDir/speedtest-bandwidth.rrd:DOWN:AVERAGE" \
                        DEF:DMIN="$RRDGraphsDir/speedtest-bandwidth.rrd:DOWN:MIN" \
                        DEF:DMAX="$RRDGraphsDir/speedtest-bandwidth.rrd:DOWN:MAX" \
                        DEF:U="$RRDGraphsDir/speedtest-bandwidth.rrd:UP:AVERAGE" \
                        DEF:UMIN="$RRDGraphsDir/speedtest-bandwidth.rrd:UP:MIN" \
                        DEF:UMAX="$RRDGraphsDir/speedtest-bandwidth.rrd:UP:MAX" \
                        CDEF:Y0=U,0,* \
                        CDEF:NegU=U,-1,* \
                        VDEF:Yavg=Y0,AVERAGE \
                        VDEF:Davg=D,AVERAGE \
                        VDEF:Uavg=NegU,AVERAGE \
                        VDEF:Uavg2=U,AVERAGE \
                        AREA:D#61ab3d:"Download" \
                        AREA:NegU#3d61ab:"Upload" \
                        LINE1:Uavg#cc1100: \
                        LINE1:Davg#cc3300:"Avg\n" \
                        LINE1:Yavg#111111: \
                        GPRINT:D:LAST:"Last download bandwidth\: %2.1lf Mb/s\n" \
                        GPRINT:U:LAST:"Last upload bandwidth\: %2.1lf Mb/s\n" \
                        GPRINT:Davg:"Avg download bandwidth\: %2.1lf Mb/s\n" \
                        GPRINT:Uavg2:"Avg upload bandwidth\: %2.1lf Mb/s" \
                        -h 500 -w 1000 \
                        -c BACK#EEEEEE00 \
                        -c SHADEA#EEEEEE00 \
                        -c SHADEB#EEEEEE00 \
                        -c CANVAS#FFFFFF00 \
                        -c GRID#a5a5a5 \
                        -c MGRID#FF9999 \
                        -c FRAME#5e5e5e \
                        -c ARROW#5e5e5e \
                        -R normal \
                        -c FONT#000000 \
                        --font LEGEND:8:DejaVuSansMono \
                        --font AXIS:7:DejaVuSansMono > /dev/null 2>&1
                done
                ;;

        (*)
                echo "Invalid option. Nothing to do. Please try again with: create - run - graph"
                ;;
        esac
