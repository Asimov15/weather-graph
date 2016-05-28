#!/usr/bin/env python
# -*- coding: utf-8 -*-

from pylab import rcParams
rcParams['figure.figsize'] = 21, 9

import sys
import codecs
import urllib, json
import matplotlib
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
import datetime
import numpy as np
from numpy import arange
from datetime import datetime, timedelta, tzinfo, date
from dateutil import tz
from matplotlib import style
from matplotlib.ticker import MultipleLocator, FormatStrFormatter
from matplotlib.dates import DayLocator, HourLocator, DateFormatter, drange

url        				= "http://www.bom.gov.au/fwo/IDV60901/IDV60901.95936.json"
response  				= urllib.urlopen(url)
data1     				= json.loads(response.read())
air_temp    			= []
local_date_times       	= []
degree_sign 			= unichr(176)
max_air_temp			= 0.0
date_sel     			= datetime.today().date()
max_airi                = 0

if len(sys.argv) == 1:
	outfn   			= "test.png"		
elif len(sys.argv) == 2:	
	outfn   			= sys.argv[1]
elif len(sys.argv) > 2:
	date_par     		= sys.argv[2]
	date_sel     		= date(int(date_par[-4:]), int(date_par[3:-4]), int(date_par[:2]))
	outfn   			= sys.argv[1]

font 					= \
{
	'family' : 'normal',
    'weight' : 'bold',
    'size'   : 22
}

head = data1['observations']['header']
loc = head[0]["name"]

for data2 in data1['observations']['data']:	

	local_date_time = (data2["local_date_time_full"])
	local_date_timef = datetime(int(local_date_time[:4]), int(local_date_time[4:-8]), int(local_date_time[6:-6]), int(local_date_time[8:-4]), int(local_date_time[10:-2]))

	if local_date_timef.date() == date_sel:	
		local_date_times.append(local_date_timef)
		air_tempf = float(data2["air_temp"])
		if air_tempf > max_air_temp:
			max_air_temp = air_tempf			
			max_index = len(local_date_times) - 1
			
		air_temp.append(air_tempf)	

dates = mdates.date2num(local_date_times)
fig, ax = plt.subplots()
lines = ax.plot_date(dates,air_temp,'b-')

t1 = datetime(date_sel.year, date_sel.month, date_sel.day)
t2 = datetime(date_sel.year, date_sel.month, date_sel.day + 1)
ax.set_xlim(t1,t2)

ax.xaxis.set_major_locator(HourLocator(interval=2))

ax.xaxis.set_major_formatter(DateFormatter('%H:%M'))

for label in ax.xaxis.get_ticklabels():
	label.set_rotation(45)
	
plt.subplots_adjust(left=0.05, bottom=0.20, right=0.96, top=0.96, wspace=0.2, hspace=0.0)

today = datetime.today()

plt.xlabel("Time")

plt.grid(b=True, which='both', color='0.65',linestyle='-')

plt.title("Temperature Graph For Date: {0} @ {1}".format(date_sel.strftime("%d/%m/%y"), loc))

plt.ylabel(u'Temperature {0}C'.format(degree_sign))

matplotlib.rc('font', **font)
#ax.annotate('Maximum', 
			 #(dates[max_index], air_temp[max_index]), 
			 #xytext=(0.7, 0.7), 
			 #textcoords=('axes fraction'),
			 #arrowprops=dict(facecolor='black', shrink=0.05),
			 #fontsize='14',
			 #color='black',
			 #horizontalalignment='right',
			 #verticalalignment='top')
			 
plt.setp(lines, linewidth=4.0)
plt.savefig('/var/www/html/weather-graph/images/' + outfn)

