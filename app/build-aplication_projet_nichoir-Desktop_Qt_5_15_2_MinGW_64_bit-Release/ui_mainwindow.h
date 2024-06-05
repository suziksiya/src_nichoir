/********************************************************************************
** Form generated from reading UI file 'mainwindow.ui'
**
** Created by: Qt User Interface Compiler version 5.15.2
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_MAINWINDOW_H
#define UI_MAINWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QApplication>
#include <QtWidgets/QCheckBox>
#include <QtWidgets/QFrame>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QMainWindow>
#include <QtWidgets/QMenuBar>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QStatusBar>
#include <QtWidgets/QVBoxLayout>
#include <QtWidgets/QWidget>

QT_BEGIN_NAMESPACE

class Ui_MainWindow
{
public:
    QWidget *centralwidget;
    QLabel *img_s;
    QLabel *img_i;
    QFrame *horizontalFrame;
    QHBoxLayout *horizontalLayout;
    QWidget *layoutWidget;
    QVBoxLayout *verticalLayout_3;
    QVBoxLayout *verticalLayout_2;
    QCheckBox *checkBoxTemp;
    QCheckBox *checkBoxHum;
    QCheckBox *checkBoxPoids;
    QVBoxLayout *verticalLayout;
    QSpacerItem *verticalSpacer;
    QLineEdit *lineIP;
    QLineEdit *lineLogin;
    QLineEdit *linePwd;
    QPushButton *pushButton;
    QMenuBar *menubar;
    QStatusBar *statusbar;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName(QString::fromUtf8("MainWindow"));
        MainWindow->resize(1014, 939);
        centralwidget = new QWidget(MainWindow);
        centralwidget->setObjectName(QString::fromUtf8("centralwidget"));
        img_s = new QLabel(centralwidget);
        img_s->setObjectName(QString::fromUtf8("img_s"));
        img_s->setGeometry(QRect(10, 610, 361, 251));
        img_i = new QLabel(centralwidget);
        img_i->setObjectName(QString::fromUtf8("img_i"));
        img_i->setGeometry(QRect(460, 600, 291, 261));
        horizontalFrame = new QFrame(centralwidget);
        horizontalFrame->setObjectName(QString::fromUtf8("horizontalFrame"));
        horizontalFrame->setGeometry(QRect(40, 20, 781, 561));
        horizontalLayout = new QHBoxLayout(horizontalFrame);
        horizontalLayout->setObjectName(QString::fromUtf8("horizontalLayout"));
        layoutWidget = new QWidget(centralwidget);
        layoutWidget->setObjectName(QString::fromUtf8("layoutWidget"));
        layoutWidget->setGeometry(QRect(840, 50, 137, 191));
        verticalLayout_3 = new QVBoxLayout(layoutWidget);
        verticalLayout_3->setObjectName(QString::fromUtf8("verticalLayout_3"));
        verticalLayout_3->setContentsMargins(0, 0, 0, 0);
        verticalLayout_2 = new QVBoxLayout();
        verticalLayout_2->setObjectName(QString::fromUtf8("verticalLayout_2"));
        checkBoxTemp = new QCheckBox(layoutWidget);
        checkBoxTemp->setObjectName(QString::fromUtf8("checkBoxTemp"));

        verticalLayout_2->addWidget(checkBoxTemp);

        checkBoxHum = new QCheckBox(layoutWidget);
        checkBoxHum->setObjectName(QString::fromUtf8("checkBoxHum"));

        verticalLayout_2->addWidget(checkBoxHum);

        checkBoxPoids = new QCheckBox(layoutWidget);
        checkBoxPoids->setObjectName(QString::fromUtf8("checkBoxPoids"));

        verticalLayout_2->addWidget(checkBoxPoids);


        verticalLayout_3->addLayout(verticalLayout_2);

        verticalLayout = new QVBoxLayout();
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        verticalSpacer = new QSpacerItem(20, 40, QSizePolicy::Minimum, QSizePolicy::Expanding);

        verticalLayout->addItem(verticalSpacer);

        lineIP = new QLineEdit(layoutWidget);
        lineIP->setObjectName(QString::fromUtf8("lineIP"));

        verticalLayout->addWidget(lineIP);

        lineLogin = new QLineEdit(layoutWidget);
        lineLogin->setObjectName(QString::fromUtf8("lineLogin"));

        verticalLayout->addWidget(lineLogin);

        linePwd = new QLineEdit(layoutWidget);
        linePwd->setObjectName(QString::fromUtf8("linePwd"));

        verticalLayout->addWidget(linePwd);

        pushButton = new QPushButton(layoutWidget);
        pushButton->setObjectName(QString::fromUtf8("pushButton"));

        verticalLayout->addWidget(pushButton);


        verticalLayout_3->addLayout(verticalLayout);

        MainWindow->setCentralWidget(centralwidget);
        menubar = new QMenuBar(MainWindow);
        menubar->setObjectName(QString::fromUtf8("menubar"));
        menubar->setGeometry(QRect(0, 0, 1014, 22));
        MainWindow->setMenuBar(menubar);
        statusbar = new QStatusBar(MainWindow);
        statusbar->setObjectName(QString::fromUtf8("statusbar"));
        MainWindow->setStatusBar(statusbar);

        retranslateUi(MainWindow);
        QObject::connect(checkBoxTemp, SIGNAL(clicked()), MainWindow, SLOT(afficher_temp()));
        QObject::connect(checkBoxHum, SIGNAL(clicked()), MainWindow, SLOT(afficher_hum()));
        QObject::connect(checkBoxPoids, SIGNAL(clicked()), MainWindow, SLOT(afficher_poids()));

        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QCoreApplication::translate("MainWindow", "MainWindow", nullptr));
        img_s->setText(QCoreApplication::translate("MainWindow", "TextLabel", nullptr));
        img_i->setText(QCoreApplication::translate("MainWindow", "TextLabel", nullptr));
        checkBoxTemp->setText(QCoreApplication::translate("MainWindow", "temp\303\251rature int/ext", nullptr));
        checkBoxHum->setText(QCoreApplication::translate("MainWindow", " humidit\303\251 int/ext", nullptr));
        checkBoxPoids->setText(QCoreApplication::translate("MainWindow", "Poids", nullptr));
        lineIP->setText(QCoreApplication::translate("MainWindow", "172.21.28.52", nullptr));
        pushButton->setText(QCoreApplication::translate("MainWindow", "connnection", nullptr));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_MAINWINDOW_H
