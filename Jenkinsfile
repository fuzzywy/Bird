#!groovy
pipeline {
    agent any
    stages {
        stage('Build') {
	    steps {
	        sh 'git clone https://eDeploy:ehub1234@github.com/fuzzywy/Docker-Bird.git'
                cd Docker-Bird
		sh 'chmod +x build/build-product.sh'
		sh 'build/build-product.sh'
	        sh 'rm -rf ../Docker-Bird'
	    }
	}
	stage('Test') {
	    steps {
	        echo 'Testing...'
	    }
	}
	stage('Deploy'){
	    steps {
	        echo 'Deploying...'
	    }
	}
    }
}
