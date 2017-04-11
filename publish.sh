#!/bin/bash
scp -i ~/.ssh/google_compute_engine -r /http/server.nicechat.com/* root@104.155.220.57:/var/www/server.nicechat.me/
