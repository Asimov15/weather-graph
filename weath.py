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

#style.use('fivethirtyeight')
url        	= "http://www.bom.gov.au/fwo/IDV60901/IDV60901.95936.json"
response  	= urllib.urlopen(url)
data1     	= json.loads(response.read())
i         	= 0
air_temp    = []
temp3       = []
degree_sign = unichr(176)

if len(sys.argv) == 1:
	outfn   = "test.png"
elif len(sys.argv) == 2:
	td      = datetime.today().date()
	outfn   = sys.argv[1]
elif len(sys.argv) > 2:
	td1     = sys.argv[2]
	td     	= date(int(td1[-4:]), int(td1[3:-4]), int(td1[:2]))
	outfn   = sys.argv[1]

font 		= \
{
	'family' : 'normal',
    'weight' : 'bold',
    'size'   : 22
}

head = data1['observations']['header']
loc = head[0]["name"]

for data2 in data1['observations']['data']:	

	temp1 = (data2["local_date_time_full"])
	temp2 = datetime(int(temp1[:4]), int(temp1[4:-8]), int(temp1[6:-6]), int(temp1[8:-4]), int(temp1[10:-2]))

	if temp2.date() == td:	
		temp3.append(temp2)
		air_temp.append(data2["air_temp"])	

dates = mdates.date2num(temp3)
fig, ax = plt.subplots()
lines = ax.plot_date(dates,air_temp,'b-')

t1 = datetime(td.year, td.month, td.day)
t2 = datetime(td.year, td.month, td.day + 1)
ax.set_xlim(t1,t2)

ax.xaxis.set_major_locator(HourLocator(interval=2))

ax.xaxis.set_major_formatter(DateFormatter('%H:%M'))

for label in ax.xaxis.get_ticklabels():
	label.set_rotation(45)
	
plt.subplots_adjust(left=0.05, bottom=0.20, right=0.96, top=0.96, wspace=0.2, hspace=0.0)

today = datetime.today()

plt.xlabel("Time")

plt.grid(b=True, which='both', color='0.65',linestyle='-')

plt.title("Temperature Graph For Date: {0} @ {1}".format(td.strftime("%d/%m/%y"), loc))

plt.ylabel(u'Temperature {0}C'.format(degree_sign))

matplotlib.rc('font', **font)

plt.setp(lines, linewidth=4.0)
plt.savefig('/var/www/html/weather/images/' + outfn)

