"use strict";

module.exports = function(grunt) {

    // Load all tasks
    require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);

    require('time-grunt')(grunt);

    grunt.initConfig({

        // Compile scss
        compass: {
            dist: {
                options: {
                    force: true,
                    config: 'config.rb',
                    outputStyle: 'compressed'
                }
            }
        },

        // Concat and minify javascripts
        uglify: {
            options: {
                compress: {
                    drop_console: true
                }
            },
            dist: {
                files: {
                    'dist/assets/scripts/app.min.js': [
                        'app/assets/scripts/plugins.js',
                        'app/assets/scripts/main.js'
                    ]
                }
            }
        },

        concat: {
            dist: {
                files: {
                    'dist/assets/scripts/app.min.js': [
                        'app/assets/scripts/plugins.js',
                        'app/assets/scripts/main.js'
                    ]
                }
            },
        },

        clean: {
            dist: {
                src: ["dist/"]
            }
        },

        copy: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'app/',
                    src: [
                        '**',
                        '*.{md,txt,htaccess}',
                        '!assets/scripts/**/*',
                        '!assets/bower_components/**/*'
                    ],
                    dest: 'dist/',
                    dot: true
                }]
            }
        },

        htmlmin: {
            dist: {
                options: {
                    removeComments: true,
                    collapseWhitespace: true
                },
                files: [{
                    expand: true,
                    cwd: 'app/',
                    src: '*.html',
                    dest: 'dist/',
                }],
            }
        },

        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 3
                },
                files: [{
                    expand: true,
                    cwd: 'src/images',
                    src: ['*.{png,jpg,gif}'],
                    dest: 'dist/'
                }],
            }
        },

        // concurrent: {
        //     dev: ['watch', 'connect']
        // },

        csslint: {
            options: {
                csslintrc: '.csslintrc'
            },
            src: ['dist/assets/styles/*.css']
        },

        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            all: ['app/assets/scripts/*.js']
        },

        connect: {
            server: {
                options: {
                    port: 8001,
                    base: 'dist',
                    livereload: true
                    // keepalive: true
                }
            }
        },

        // FTP deployment
        // 'ftp-deploy': {
        //     build: {
        //         auth: {
        //             host: 'ftp.domain.com',
        //             port: 21,
        //             authKey: 'key'
        //         },
        //         src: 'app/',
        //         dest: '/www/',
        //         exclusions: [
        //             '**/.DS_Store',
        //             '**/Thumbs.db',
        //             '.git',
        //             '.gitignore',
        //             'README.md',
        //             '.ftppass',
        //             'bower.json',
        //             'config.rb',
        //             'npm-debug.log',
        //             'sftp-config.json'
        //         ]
        //     }
        // },

        // Watch
        watch: {
            options: {
                livereload: true
            },
            compass: {
                files: ['app/assets/sass/**/*.scss'],
                tasks: ['compass']
            },
            js: {
                files: 'app/assets/scripts/**/*',
                tasks: ['concat']
            },
            htmlmin: {
                files: 'app/*.html',
                tasks: ['htmlmin']
            },
            grunt: {
                files: ['Gruntfile.js']
            }
        }
    });

    // Desenvolvimento
    grunt.registerTask('dev', ['connect:server', 'watch']);

    // registrando tarefa default
    grunt.registerTask('build', [
        'clean',
        'copy:dist',
        'uglify:dist',
        'htmlmin:dist',
        'imagemin:dist'
    ]);

    // Testes
    grunt.registerTask('lint', ['csslint', 'jshint:all']);

    // Deploy
    grunt.registerTask('deploy', ['ftp-deploy']);
};
