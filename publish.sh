#!/bin/bash
scp -i ~/.ssh/google_compute_engine -r /http/server.nicechat.me/* root@104.155.220.57:/var/www/server.nicechat.me/
