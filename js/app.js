var app = angular.module('callouts', []);
app.controller('calloutsCtrl', function($scope, $http) {

    //function to add new callout
    $scope.addCallout = function(){
        //send callout form (currentCallout) as post
        $http.post("callouts", $scope.currentCallout)
            .success(function(response){
                //add the received id into the currentCallout
                $scope.currentCallout.id = response;
                //add currentCaklout to callout list
                $scope.callouts.push($scope.currentCallout);
                //reset form
                $scope.resetForm();
                alert("Callout added successfully");
            })
            .error(function(){
                alert("Your Callout could not be added. Try again soon.");
            });
    };

    //function to update callout
    $scope.updateCallout = function(){
        //send callout form (currentCallout) as put
        $http.put("callouts", $scope.currentCallout)
            .success(function(){
                //find the updated callout in list and update
                $scope.callouts.forEach(function(callout, index){
                    if (callout.id == $scope.currentCallout.id) {
                        $scope.callouts[index] = $scope.currentCallout;
                        $scope.resetForm();
                    }
                });
                alert("Callout updated successfully");
            })
            .error(function(){
                alert("Your Callout could not be updated. Try again soon.");
            });
    };

    //function to delete callout
    $scope.deleteCallout = function(id){
        $http.delete("callouts/" + id)
            .success(function(){
                //remove deleted callout from list
                $scope.callouts.forEach(function(callout, index){
                    if (callout.id == id) {
                        $scope.callouts.splice(index, 1);
                    }
                });
                alert("Callout deleted successfully");
            })
            .error(function(){
                alert("Your Callout could not be deleted. Try again soon.");
            });
    };

    //on clicking edit button, fill out form and change form text
    $scope.editClick = function(id){
        $scope.callouts.forEach(function(callout){
            if (callout.id == id){
                //copy callout details
                $scope.currentCallout = angular.copy(callout);
                //change form details
                $scope.calloutForm = {
                    action: $scope.updateCallout,
                    buttonText: "Update",
                    titleText: "Update Callout"
                };
            }
        });
    };

    //function to reset form and text
    $scope.resetForm = function() {
        $scope.currentCallout = {
            title: "",
            type: "problem 1",
            message: "",
            id: ""
        };
        $scope.calloutForm = {
            action: $scope.addCallout,
            buttonText: "Add",
            titleText: "New Callout"
        };
    };

    //set callouts view as list
    $scope.setList = function() {
        $scope.calloutView = "list";
    };
    //set callouts view as grid
    $scope.setGrid = function() {
        $scope.calloutView = "grid";
    };

    //on loading page, reset form, set view as list and get current callouts
    $scope.resetForm();
    $scope.calloutView = "list";
    $http.get("callouts")
        .success(function(response){
            $scope.callouts = response;
        })
        .error(function(){
            alert("Couldn't get callouts. Try again soon.");
        });
});