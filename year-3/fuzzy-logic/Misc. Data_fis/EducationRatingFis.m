% To remove the warning messages for using old syntax
warning('off','fuzzy:general:warnDeprecation_Newfis') 
warning('off','fuzzy:general:warnDeprecation_Addvar')
warning('off','fuzzy:general:warnDeprecation_Addmf')
warning('off','fuzzy:general:warnDeprecation_Evalfis')

% Clears the Command Window of clutter
clc
%Different options for FIS

%                                       AND OR    Impl Agg  Defuzzification
a=newfis('EducationDeliverables','mamdani','min','max', 'min','max','centroid');
%a=newfis('EducationDeliverables','mamdani','min','max', 'min','max','mom');
%a=newfis('EducationDeliverables','mamdani','min','max', 'min','max','lom');
%a=newfis('EducationDeliverables','mamdani','min','max', 'min','max','som');
%a=newfis('EducationDeliverables','mamdani','min','max', 'min','max','bisector');

%                                               AND OR    Impl Agg  Defuzzification
%a=newfis('EducationDeliverables','mamdani','prod','probor', 'prod','max','centroid');
%a=newfis('EducationDeliverables','mamdani','prod','probor', 'prod','max','mom');
%a=newfis('EducationDeliverables','mamdani','prod','probor', 'prod','max','lom');
%a=newfis('EducationDeliverables','mamdani','prod','probor', 'prod','max','som');
%a=newfis('EducationDeliverables','mamdani','prod','probor', 'prod','max','bisector');

% Declaring a new variable - this is an INPUT(1)
a = addvar(a, 'input', 'Teaching Level', [0 100]);

% Populating the 1st input variable
% Based on the output of the first system
a = addmf(a, 'input', 1, 'Beginner', 'trapmf', [0 0 10 30]);
a = addmf(a, 'input', 1, 'High Beginner', 'trimf', [15 35 45]);
a = addmf(a, 'input', 1, 'Intermediate', 'trimf', [25 50 60]);
a = addmf(a, 'input', 1, 'High Intermediate', 'trimf', [40 65 75]);
a = addmf(a, 'input', 1, 'Advanced', 'trapmf',  [60 90 100 100]);

% Declaring a new variable - this is an INPUT(2)
% Based on how often, and how long a Tech subject is taught each week
a=addvar(a,'input','Class Deliverables % (Weekly Occurrences)',[0 100]);

% Populating the 2nd input variable with membership functions
a = addmf(a, 'input', 2, 'Low', 'trapmf', [0 0 10 25]);
a = addmf(a, 'input', 2, 'Below Average', 'trimf', [10 30 40]);
a = addmf(a, 'input', 2, 'Average', 'trimf', [20 40 60]);
a = addmf(a, 'input', 2, 'Above Average', 'trimf', [40 65 75]);
a = addmf(a, 'input', 2, 'High', 'trapmf', [60 90 100 100]);

% Declaring a new variable - this is an INPUT(3)
% Based on students age group, allows me to insure the grading is accurate per age group
a = addvar(a, 'input', 'Student Year Group)', [0 100]);

% Populating the 3rd input variable with membership functions
a = addmf(a, 'input', 3, 'Year 7', 'trapmf', [0 0 10 20]);
a = addmf(a, 'input', 3, 'Year 8', 'trimf', [10 25 40]);
a = addmf(a, 'input', 3, 'Year 9', 'trimf',  [20 50 60]);
a = addmf(a, 'input', 3, 'Year 10', 'trimf', [45 70 80]);
a = addmf(a, 'input', 3, 'Year 11', 'trapmf', [60 90 100 100]); 

% Declaring a new variable - this is an INPUT(4)
% Based on a scoring out of 1-10 with 10 being excellent and 1 being poor
a = addvar(a, 'input', 'Student Feedback (scale of 1-10)', [0 100]);

% Populating the 4th input variable with membership functions
a = addmf(a, 'input', 4, 'Low Rating', 'trapmf', [0 0 20 30]);
a = addmf(a, 'input', 4, 'Inadequate Rating', 'trimf', [10 40 45]);
a = addmf(a, 'input', 4, 'Average Rating', 'trimf',  [30 50 60]);
a = addmf(a, 'input', 4, 'Above Average Rating', 'trimf',  [40 60 75]);
a = addmf(a, 'input', 4, 'Excellent Rating', 'trapmf',  [60 90 100 100]);

% Declaring a new variable - this is an OUTPUT(1)
a=addvar(a,'output','Overall Deliverables Rating (%)',[0 100]);

% Populating the output variable with membership functions
a = addmf(a, 'output', 1, 'Poor', 'trapmf', [0 0 10 30]);
a = addmf(a, 'output', 1, 'Low Average', 'trimf', [20 35 45]);
a = addmf(a, 'output', 1, 'Mid_Range', 'trimf', [30 50 60]);
a = addmf(a, 'output', 1, 'High Average', 'trimf', [40 65 75]);
a = addmf(a,'output', 1,'Excellent','trapmf',[55 90 100 100]);

% The rule declarations

%[Rules]

% Experimentation
%rule1 = [1 1 1 1 0.2 2];
%rule2 = [2 1 2 1 0.4 2];
%rule3 = [1 2 2 1 0.6 2];
%rule4 = [2 2 2 1 0.8 2];
%rule5 = [1 2 1 1 1 2];
%rule6 = [1 1 1 1 0.2 1];
%rule7 = [2 1 2 1 0.4 1];
%rule8 = [1 2 2 1 0.6 1];
%rule9 = [2 2 2 1 0.8 1];
%rule10 = [1 2 1 1 1 1];

% Final Test Rules
rule1 = [1 1 1 1 1 1 1];
rule2 = [2 2 1 2 4 1 1];
rule3 = [4 3 1 3, 5 1 1];
rule4 = [4 5 1 5, 3 1 1];
rule5 = [1 3 1 3, 2 1 1];
rule6 = [1 2 2 2, 1 1 1];
rule7 = [3 1 2 2, 4 1 1];
rule8 = [2 3 2 3, 2 1 1];
rule9 = [2 3 2 4, 5 1 1];
rule10 = [2 5 2 5, 3 1 1];
rule11 = [1 1 2 1, 1 1 1];
rule12 = [4 2 2 2, 4 1 1];
rule13 = [2 3 2 3, 2 1 1];
rule14 = [2 3 2 4, 5 1 1];
rule15 = [5 5 2 5, 3 1 1];
rule16 = [1 1 3 1, 1 1 1];
rule17 = [4 2 3 3, 4 1 1];
rule18 = [2 3 3 3, 2 1 1];
rule19 = [5 4 3 4, 5 1 2];
rule20 = [5 3 3 4, 5 1 1];
rule21 = [5 5 3 5, 3 1 1];
rule22 = [5 4 3 5, 3 1 2];
rule23 = [4 2 4 2, 1 1 1];
rule24 = [4 2 4 2, 4 1 1];
rule25 = [5 4 4 3, 2 1 1];
rule26 = [5 4 4 4, 5 1 1];
rule27 = [3 4 4 5, 3 1 1];
rule28 = [2 1 5 1, 1 1 1];
rule29 = [5 1 5 2, 4 1 1];
rule30 = [3 3 5 3, 2 1 1];
rule31 = [5 4 5 4, 5 1 1];
rule32 = [3 5 5 5, 3 1 1];

% Poor
%rule1 = [1 1 1 1 1 1 1];
%rule2 = [1 1 1 2 1 1 1];
%rule3 = [1 2 1 2 1 1 1];
%rule4 = [2 1 1 2 1 1 1];
%rule6 = [1 1 2 1 1 1 1];
%rule7 = [1 2 2 2 1 1 1];
%rule8 = [1 1 2 2 1 1 1];
%rule9 = [2 1 2 2 1 1 1];
%rule11 = [2 2 1 1 1 1 1];
%rule12 = [1 2 1 1 1 1 1];
%rule14 = [1 2 2 1 1 1 1];
%rule17 = [2 2 2 2 1 1 1];
%rule18 = [2 1 2 1 1 1 1];
%rule19 = [2 2 2 1 1 1 1];
%rule20 = [2 1 1 1 1 1 1];
%rule24 = [2 2 1 2 1 1 1];

% Below Average
%rule25 = [1 3 1 3 2 1 1];
%rule26 = [4 2 1 3 2 1 1];
%rule27 = [4 2 1 3 2 1 1];
%rule28 = [4 1 1 3 2 1 1];
%rule29 = [2 3 1 3 2 1 1];
%rule30 = [2 3 2 3 2 1 1];
%rule31 = [4 3 2 3 2 1 1];
%rule32 = [5 3 2 3 2 1 1];
%rule33 = [5 2 2 3 2 1 1];
%rule34 = [2 1 2 4 2 1 1];
%rule35 = [5 2 3 4 2 1 1];
%rule36 = [2 3 3 4 2 1 1];
%rule37 = [5 3 3 3 2 1 1];
%rule38 = [5 2 3 3 2 1 1];
%rule39 = [2 4 3 3 2 1 1];
%rule40 = [2 4 4 3 2 1 1];
%rule41 = [2 3 4 3 2 1 1];
%rule42 = [5 3 4 3 2 1 1];
%rule43 = [2 4 4 3 2 1 1];
%rule44 = [2 2 4 4 2 1 1];
%rule45 = [2 3 5 4 2 1 1];
%rule46 = [5 2 5 4 2 1 1];
%rule47 = [3 3 5 3 2 1 1];
%rule48 = [2 2 5 4 2 1 1];
%rule49 = [2 3 5 3 2 1 1];

%Average
%rule50 = [1 5 1 5 3 1 1];
%rule51 = [4 5 1 5 3 1 1];
%rule52 = [2 5 1 5 3 1 1];
%rule53 = [4 5 2 5 3 1 1];
%rule54 = [2 5 2 5 3 1 1];
%rule55 = [5 5 2 5 3 1 1];
%rule56 = [2 5 3 5 3 1 1];
%rule57 = [5 5 3 5 3 1 1];
%rule58 = [3 5 3 5 3 1 1];
%rule59 = [5 5 4 5 3 1 1];
%rule60 = [3 5 4 5 3 1 1];
%rule61 = [5 5 5 5 3 1 1];
%rule62 = [3 5 5 5 3 1 1];

% Above Average
%rule63 = [1 2 1 2 4 1 1];
%rule64 = [4 2 1 2 4 1 1];
%rule65 = [3 2 1 2 4 1 1];
%rule67 = [1 2 1 3 4 1 1];
%rule68 = [1 2 1 3 4 1 1];
%rule69 = [1 1 2 3 4 1 1];
%rule70 = [2 2 2 2 4 1 1];
%rule71 = [3 1 2 2 4 1 1];
%rule72 = [3 2 2 1 4 1 1];
%rule73 = [5 1 2 1 4 1 1];
%rule74 = [5 1 3 1 4 1 1];
%rule75 = [5 2 3 2 4 1 1];
%rule76 = [2 2 3 1 4 1 1];
%rule77 = [3 1 3 2 4 1 1];
%rule78 = [5 2 3 2 4 1 1];
%rule79 = [2 3 4 1 4 1 1];
%rule80 = [5 1 4 1 4 1 1];
%rule81 = [1 3 4 2 4 1 1];
%rule82 = [4 2 4 2 4 1 1];
%rule83 = [3 1 4 1 4 1 1];
%rule84 = [3 1 5 1 4 1 1];
%rule85 = [3 2 5 2 4 1 1];
%rule86 = [2 2 5 2 4 1 1];
%rule87 = 1 1 5 2 4 1 1[];
%rule88 = [5 2 5 1 4 1 1];


% Excellent
%rule89 = [1 4 1 4 5 1 1];
%rule90 = [4 4 1 4 5 1 1];
%rule91 = [2 5 1 4 5 1 1];
%rule92 = [3 2 1 5 5 1 1];
%rule93 = [2 4 1 4 5 1 1];
%rule94 = [2 4 2 4 5 1 1];
%rule95 = [5 4 2 4 5 1 1];
%rule96 = [2 3 2 4 5 1 1];
%rule97 = [4 2 2 5 5 1 1];
%rule98 = [3 5 2 3 5 1 1];
%rule99 = [3 5 3 3 5 1 1];
%rule100 = [5 4 3 3 5 1 1];
%rule101 = [5 3 3 4 5 1 1];
%rule102 = [2 3 3 4 5 1 1];
%rule103 = [2 4 3 4 5 1 1];
%rule104 = [5 4 4 4 5 1 1];
%rule105 = [2 4 4 4 5 1 1];
%rule106 = [3 5 4 3 5 1 1];
%rule107 =[3 4 4 4 5 1 1];
%rule108 = [5 3 4 4 5 1 1];
%rule109 =[3 4 5 4 5 1 1];
%rule110 = [5 4 5 4 5 1 1];
%rule111 =[5 3 5 4 5 1 1];
%rule112 = [2 2 5 5 5 1 1];
%rule113 =[5 2 5 5 5 1 1];

% A matrix to hold the rule arrays
 ruleList = [rule1; rule2; rule3; rule4; rule5; rule6; rule7; rule8; rule9; rule10; rule11; 
    rule12; rule13; rule14; rule15; rule16; rule17; rule18; rule19; rule20; rule21; rule22; rule23; rule24; rule25; rule26; rule27; rule28; rule29; rule30; rule31; rule32;]; %rule33; rule34; rule35; rule36; rule37; rule38; rule39; rule40
    %rule41; rule42; rule43; rule44; rule45; rule46; rule47; rule48; rule49; rule50; rule51; rule52; rule53; rule54; rule55; rule56;
    %rule57; rule58; rule59; rule60; rule61; rule62; rule63; rule64; rule65; rule67; rule68; rule69; rule70; rule71; rule72; rule73; 
    %rule74; rule75; rule76; rule77; rule78; rule79; rule80; rule81; rule82; rule83; rule84; rule85; rule86; rule87; rule88; rule89;
    %rule90; rule91; rule92; rule93; rule94; rule95; rule96; rule97; rule98; rule99; rule100; rule101; rule102; rule103; rule104;
    %rule105; rule106; rule107; rule108; rule109; rule110; rule111; rule112; rule113;];

% Print the rules to the command window
showrule(a)

% Add the rules to the fis
a = addRule(a, ruleList);

% A varaible to hold the excel file
data = ('EducationData.xlsx');

% Read in the values and store the in testData
EducationData = xlsread(data);

% A for loop to process the data and output the results(fileName,sheetNo.)
for i=1:size(EducationData, 1)
       % Evaluate the fuzzy system
       output = evalfis([EducationData(i, 1), EducationData(i, 2), EducationData(i, 3), EducationData(i, 4) ], a); 
       fprintf('%d) In(1): %.2f, In(2) %.2f, In(3) %.2f, In(4) %.2f => Out: %.2f \n\n',i,EducationData(i, 1),EducationData(i, 2),EducationData(i, 3),EducationData(i, 4),output);  
       % The following section changes where the output is saved/printed (%H%d’,I+1) (H is column No.)
       xlswrite('EducationData.xlsx', output, 3, sprintf('F%d',i+1));
end

% The ruleview allows you to see the rule-base
ruleview(a)

% The subplots to visualise the system
figure(1)
subplot(5,1,1), plotmf(a, 'input', 1)
subplot(5,1,2), plotmf(a, 'input', 2)
subplot(5,1,3), plotmf(a, 'input', 3)
subplot(5,1,4), plotmf(a, 'input', 4)
subplot(5,1,1), plotmf(a, 'output', 1)

figure(2)
subplot(1,1,1), plotmf(a, 'output', 1)
