library(jsonlite)
library(recommenderlab)

args<-commandArgs(TRUE)
load("/home/rstudio/model.RData")

rating_frame<-jsonlite::fromJSON(args[1])
travel_frame<-jsonlite::fromJSON("/home/rstudio/unique_travel.json")

travel_list<-travel_frame[[1]]

mat<-matrix(nrow=1,ncol=length(travel_list),byrow=TRUE)
rownames(mat)<-args[2]
colnames(mat)<-travel_list

tryCatch(
  {
    for(i in 1:length(rating_frame[[1]]))
    {
      mat[args[2],rating_frame[[1]][i]]<-as.numeric(rating_frame[[2]][i]) 
    }
  },
  error = function(e)
  {
    # Error index
    print(i)
  }
)

rating<-as(mat,"realRatingMatrix")
# user에게 가장 추천할만한 다섯개 상품
recom<-predict(model,rating,n=10,type="topNList")
print(toJSON(as(recom,"list")))