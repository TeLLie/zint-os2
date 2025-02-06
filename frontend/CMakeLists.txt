# Copyright (C) 2008 by BogDan Vatra < bogdan@licentia.eu >
# Copyright (C) 2009-2024 Robin Stuart <rstuart114@gmail.com>
# vim: set ts=4 sw=4 et :

cmake_minimum_required(VERSION 3.10)
project(zint_frontend)

add_executable(${PROJECT_NAME} main.c)

if(WIN32)
    target_sources(${PROJECT_NAME} PRIVATE zint.rc)
endif()

if(OS2)
    target_sources(${PROJECT_NAME} PRIVATE zint-os2.rc)
endif(OS2)

if(OS2)
enable_language(RC)
set_source_files_properties("${CMAKE_SOURCE_DIR}/zint-os2.rc" PROPERTIES LANGUAGE RC) 
set(zint-frontend_SRCS ${zint-frontend_SRCS} "${CMAKE_SOURCE_DIR}/zint-os2.rc")
include_directories(${CMAKE_SOURCE_DIR})
endif(OS2)

target_include_directories(${PROJECT_NAME} PUBLIC "${CMAKE_CURRENT_SOURCE_DIR}/../backend")

set_target_properties(${PROJECT_NAME} PROPERTIES OUTPUT_NAME "zint")

if(ZINT_SHARED)
    target_link_libraries(${PROJECT_NAME} zint)
else()
    target_link_libraries(${PROJECT_NAME} zint-static)
endif()
if(NOT HAVE_GETOPT_LONG_ONLY)
    target_link_libraries(${PROJECT_NAME} zint_bundled_getopt)
endif()

install(TARGETS ${PROJECT_NAME} DESTINATION "${CMAKE_INSTALL_BINDIR}" RUNTIME)
if(UNIX)
    install(FILES "${CMAKE_CURRENT_SOURCE_DIR}/../docs/zint.1" DESTINATION "${CMAKE_INSTALL_MANDIR}/man1" COMPONENT doc)
endif()

if(OS2)
set_target_properties(${PROJECT_NAME} PROPERTIES
 OS2_DEF_VENDOR "TeLLie OS2 forever"
 OS2_DEF_PATCH "${ZINT_FRONTEND_VERSION_MINOR}"
 OS2_DEF_VERSION "${ZINT_FRONTEND_VERSION}"
 OS2_DEF_EXEType "WINDOWAPI")
endif()

if(ZINT_TEST)
    add_subdirectory(tests)
endif()
