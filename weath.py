#!/usr/bin/env python
# -*- coding: utf-8 -*-

import matplotlib
matplotlib.use('Agg')

from pylab import rcParams
rcParams['figure.figsize'] = 30, 12

params = {'axes.labelsize': 18,'axes.titlesize':20, 'font.size': 20, 'legend.fontsize': 20, 'xtick.labelsize': 20, 'ytick.labelsize': 20}
matplotlib.rcParams.update(params)

import socket
import argparse
import sys
import codecs
import json, requests
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

parser 					= argparse.ArgumentParser()
date_def				= datetime.today().strftime("%d%m%Y")
parser.add_argument("-f",  "--outfile",  default="test.png", help="the output filename")
parser.add_argument("-d",  "--adate"  ,  default=date_def,   help="the date")
args = parser.parse_args()

SMALL_SIZE = 8
MEDIUM_SIZE = 10
BIGGER_SIZE = 22
#url						= "http://www.bom.gov.au/fwo/IDV60701/IDV60701.95936.json"
url						= "http://www.bom.gov.au/fwo/IDV60701/IDV60701.94857.json"
response  				= requests.get(url)
data1					= response.json()
air_temp    			= []
local_date_times		= []
degree_sign 			= unichr(176)
max_air_temp			= -1000.0
min_air_temp			= 1000.0
current_temp			= 0
max_airi				= 0
outfn 					= args.outfile
date_par 				= args.adate
current_temp_date		= datetime.min
date_sel	     		= date(int(date_par[-4:]), int(date_par[2:-4]), int(date_par[:2]))

font 					= \
{
	'family' : 'normal',
	'weight' : 'bold',
	'size'   : 26
}

head = data1['observations']['header']
loc = head[0]["name"]

for data2 in data1['observations']['data']:

	local_date_time = (data2["local_date_time_full"])
	local_date_timef = datetime(int(local_date_time[:4]), int(local_date_time[4:-8]), int(local_date_time[6:-6]), int(local_date_time[8:-4]), int(local_date_time[10:-2]))

	air_tempf = float(data2["air_temp"])

	if local_date_timef.date() == date_sel:	
		local_date_times.append(local_date_timef)

		if air_tempf > max_air_temp:
			max_air_temp = air_tempf

		if air_tempf < min_air_temp:
			min_air_temp =  air_tempf

		air_temp.append(air_tempf)

	if local_date_timef > current_temp_date:
		current_temp_date = local_date_timef
		current_temp 	  = air_tempf

dates = mdates.date2num(local_date_times)
fig, ax = plt.subplots()
lines = ax.plot_date(dates,air_temp,'b-')

t1 = datetime(date_sel.year, date_sel.month, date_sel.day)
t2 = datetime(date_sel.year, date_sel.month, date_sel.day) + timedelta(days=1)
ax.set_xlim(t1,t2)

ax.xaxis.set_major_locator(HourLocator(interval=2))

ax.xaxis.set_major_formatter(DateFormatter('%H:%M'))

for label in ax.xaxis.get_ticklabels():
	label.set_rotation(45)

plt.subplots_adjust(left=0.06, bottom=0.1, right=0.98, top=0.94, wspace=0.2, hspace=0.0)

today = datetime.today()

plt.xlabel("Time")

plt.grid(b=True, which='both', color='0.65',linestyle='-')

plt.title("Temperature Graph For Date: {0} @ {1}".format(date_sel.strftime("%d/%m/%y"), loc), fontweight='bold')

plt.ylabel(u'Temperature {0}C'.format(degree_sign))

matplotlib.rc('font', **font)

plt.setp(lines, linewidth=3.0)

if socket.gethostname() == "cortona":
	save_dir = '/var/www/html/weather-graph/images/'
else:
	save_dir = '/var/www/davidzuccaro.com.au/public_html/weather-graph/images/'

plt.savefig(save_dir + outfn)

print max_air_temp
print min_air_temp
print current_temp
print save_dir
print outfn
