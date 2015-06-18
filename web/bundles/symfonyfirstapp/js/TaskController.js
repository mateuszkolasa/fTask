(function(){
    var fTask = angular.module('fTask', []).config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);

    fTask.factory('$task', ['$http', function($http) {
        var taskService = this;  
        this.temp;  
        this.tasks = [];

        $http.get('/api/tasks').success(function(data){
            taskService.tasks = data;
        });

        return {
            changeStatus: function(task) {
                var address = '{{ path('SymfonyFirstApp_status', {id: '__ID__'}) }}';
                $http.post(address.replace('__ID__', task.id)).success(function(data){
                    if(data.status) {
                        task.priority = data.priority;
                        task.status = true;
                    } else {
                        task.priority = data.priority;
                        task.status = false;
                    }
                });
            },
            getTasks: function() {
                return taskService.tasks;   
            },
            addTask: function(task, ctrl) {
                taskService.temp = false;
                $http.post('/api/add/task', task).success(function(resp) {
                    if(resp.error != undefined) {
                        alert(resp.error);
                        return false;
                    }

                    task = resp.newTask;
                    taskService.tasks.push(task);
                    ctrl.newTask = {};
                }).error(function(resp){
                    alert('Wystąpił błąd');
                });
            }
        };
    }]);
    
    fTask.controller('GridController', ['$task', function($task) {
        var grid = this;

        this.filterHideFinished = true;

        this.hideFinished = function(status) {
            grid.filterHideFinished = status;
        };

        this.statusChange = function(task) {
            $task.changeStatus(task);
        }

        this.getTasks = function() {
            return $task.getTasks();
        };
    }]);

    fTask.controller('TaskController', ['$task', function($task) {
        var to = this;
        this.newTask = {};
        
        this.add = function() {
            to.newTask.status = true;
            $task.addTask(to.newTask, to);
        };
    }]);
})();