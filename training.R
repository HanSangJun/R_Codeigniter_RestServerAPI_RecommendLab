library(jsonlite)
library(recommenderlab)

training_frame<-jsonlite::fromJSON("/home/rstudio/training.json")
member_frame<-jsonlite::fromJSON("/home/rstudio/unique_member.json")
travel_frame<-jsonlite::fromJSON("/home/rstudio/unique_travel.json")

member_list<-member_frame[[1]]
travel_list<-travel_frame[[1]]

mat<-matrix(nrow=length(member_list),ncol=length(travel_list),byrow=TRUE)
rownames(mat)<-member_list
colnames(mat)<-travel_list

tryCatch(
  {
    for(i in 1:length(training_frame[[1]]))
    {
      mat[training_frame[[1]][i],training_frame[[2]][i]]<-as.numeric(training_frame[[3]][i]) 
    }
  },
  error = function(e)
  {
    # Error index
    print(i)
  }
)

training<-as(mat,"realRatingMatrix")
model<-Recommender(training,method="POPULAR")

save(model,file="/home/rstudio/model.RData")