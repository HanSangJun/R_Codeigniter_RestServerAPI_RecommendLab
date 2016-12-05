# RestServer API using Codeigniter3 integrated with R to implement Collaborative Filtering(RecommenderLab)

### This project is conducted for...
1) 2016 Fall Semester Database System final project, GIST<br />
2) to design travel-recommender system using collaborative filtering<br />
3) Collaborative filtering is implemented by R, RecommenderLab package<br />
4) On Ubuntu 14.04 LTS, RestServer API using Codeigniter3 communicataes with R through shell command<br />
5) RestServer can commnuicate with front-end such as WEB, App etc...<br />

### The environment(only server side)
1) Ubuntu 14.04<br />
2) Codeigntier3(WEB PHP framework)<br />
3) Codeigniter3 RestServer API (https://github.com/chriskacerguis/codeigniter-restserver)<br />
4) MySQL<br />
5) R with RecommenderLab package(The way of converting table to R matrix is included)<br />

### For someone
1) who wants to integrate with machine learning system and (WEB) server<br />
2) who are having difficulty of communicationg with R and php(hard to find on Google, you can get hints)<br />

### Call API
1) Traininge ex) http://54.153.75.255:12345/index.php/HAVE/training (post call without parameters)<br />
2) Predict ex) http://54.153.75.255:12345/index.php/HAVE/predict (post call with parameter member_id)<br />

### The Files
1) HAVE.php - It is controller of codeigniter framework, you can check API function on it<br />
2) Training.R - to train collaborative filtering<br />
3) Predict.R - to predict<br />

### Made by
SangJun Han(hjun1008@gist.ac.kr), BioComputing Lab, GIST, Gwangju, South Korea<br />
